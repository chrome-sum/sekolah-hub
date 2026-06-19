<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Requests;

use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'track_id' => ['required', 'exists:admission_tracks,id'],
            'cf-turnstile-response' => [
                app()->environment('testing') ? 'nullable' : 'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (app()->environment('testing')) {
                        return;
                    }

                    $secretKey = '';
                    try {
                        $secretKeySetting = \App\Modules\System\Models\Setting::where('key', 'cloudflare.turnstile.secret_key')->first();
                        $secretKey = $secretKeySetting ? $secretKeySetting->value : '';
                    } catch (\Exception $e) {
                        $secretKey = '';
                    }

                    if (empty($secretKey)) {
                        return; // Skip Turnstile validation if secret is not set (e.g. fresh installation)
                    }

                    $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                        'secret' => $secretKey,
                        'response' => $value,
                        'remoteip' => $this->ip(),
                    ]);

                    if (!$response->successful() || !$response->json('success')) {
                        $fail('Verifikasi Turnstile (Anti-Spam) gagal. Silakan coba lagi.');
                    }
                }
            ],
        ];

        $trackId = $this->input('track_id');
        if (!$trackId && $this->route('track_slug')) {
            $track = AdmissionTrack::where('slug', $this->route('track_slug'))->first();
            $trackId = $track?->id;
        }

        if ($trackId) {
            $fields = AdmissionFormField::where('track_id', $trackId)
                ->where('is_active', true)
                ->get();

            foreach ($fields as $field) {
                $fieldRules = [];
                if ($field->is_required) {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }

                if ($field->validation_rules) {
                    $customRules = explode('|', $field->validation_rules);
                    $customRules = array_filter($customRules, function ($r) {
                        $clean = strtolower(trim($r));
                        return !in_array($clean, ['required', 'nullable']);
                    });
                    $fieldRules = array_merge($fieldRules, $customRules);
                } else {
                    switch ($field->type) {
                        case 'email':
                            $fieldRules[] = 'email';
                            break;
                        case 'number':
                            $fieldRules[] = 'numeric';
                            break;
                        case 'date':
                            $fieldRules[] = 'date';
                            break;
                        case 'file':
                            $fieldRules[] = 'file';
                            $fieldRules[] = 'max:2048'; // Default 2MB limit
                            break;
                        case 'boolean':
                            $fieldRules[] = 'boolean';
                            break;
                        case 'checkbox':
                            $fieldRules[] = 'array';
                            break;
                        case 'heading':
                        case 'description':
                            // Heading & description are informational elements, no validation required
                            continue 2;
                        default:
                            $fieldRules[] = 'string';
                            break;
                    }
                }

                $rules['fields.' . $field->field_key] = $fieldRules;
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [];
        $trackId = $this->input('track_id');
        if (!$trackId && $this->route('track_slug')) {
            $track = AdmissionTrack::where('slug', $this->route('track_slug'))->first();
            $trackId = $track?->id;
        }

        if ($trackId) {
            $fields = AdmissionFormField::where('track_id', $trackId)->get();
            foreach ($fields as $field) {
                $attributes['fields.' . $field->field_key] = $field->label;
            }
        }

        return $attributes;
    }

    protected function passedValidation(): void
    {
        $this->ensureIsNotRateLimited();
        
        // Record attempt
        RateLimiter::hit($this->throttleKey(), 300); // 300 seconds = 5 minutes
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'fields.email' => 'Terlalu banyak mengirim pendaftaran. Silakan coba lagi dalam ' . (int) ceil($seconds / 60) . ' menit.',
        ]);
    }

    protected function throttleKey(): string
    {
        return 'ppdb-submit|' . $this->ip();
    }
}
