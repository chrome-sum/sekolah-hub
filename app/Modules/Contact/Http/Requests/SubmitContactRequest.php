<?php

declare(strict_types=1);

namespace App\Modules\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class SubmitContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'cf-turnstile-response' => [
                app()->environment('testing') ? 'nullable' : 'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (app()->environment('testing')) {
                        return;
                    }

                    // Check if site/secret key settings exist
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
            'email' => 'Terlalu banyak mengirim pesan. Silakan coba lagi dalam ' . (int) ceil($seconds / 60) . ' menit.',
        ]);
    }

    protected function throttleKey(): string
    {
        return 'contact-submit|' . $this->ip();
    }
}
