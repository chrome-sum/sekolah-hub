<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Actions;

use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Models\RegistrationValue;
use App\Modules\PPDB\Models\RegistrationDocument;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SubmitRegistrationAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(array $data): Registration
    {
        return DB::transaction(function () use ($data) {
            $trackId = $data['track_id'];
            $track = AdmissionTrack::findOrFail($trackId);
            $academicYear = $track->academicYear;

            // Generate registration number: PPDB-{YEAR}-{SEQ}
            $yearStr = date('Y');
            if ($academicYear) {
                if (preg_match('/\b\d{4}\b/', $academicYear->code, $matches)) {
                    $yearStr = $matches[0];
                } elseif (preg_match('/\b\d{4}\b/', $academicYear->name, $matches)) {
                    $yearStr = $matches[0];
                }
            }

            // Lock registrations for this academic year to prevent race conditions on sequence
            $lastRegistration = Registration::where('academic_year_id', $academicYear->id)
                ->whereNotNull('registration_number')
                ->orderBy('registration_number', 'desc')
                ->lockForUpdate()
                ->first();

            $seq = 1;
            if ($lastRegistration) {
                $parts = explode('-', $lastRegistration->registration_number);
                if (count($parts) >= 3) {
                    $seq = ((int) end($parts)) + 1;
                }
            }

            $registrationNumber = sprintf('PPDB-%s-%06d', $yearStr, $seq);

            $registration = Registration::create([
                'registration_number' => $registrationNumber,
                'academic_year_id' => $academicYear->id,
                'track_id' => $track->id,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Save form fields values
            $fieldsData = $data['fields'] ?? [];
            $formFields = AdmissionFormField::where('track_id', $track->id)
                ->where('is_active', true)
                ->get();

            foreach ($formFields as $field) {
                $value = $fieldsData[$field->field_key] ?? null;

                if ($field->type === 'file') {
                    if ($value instanceof UploadedFile) {
                        $path = $value->store('ppdb', 'local');
                        RegistrationDocument::create([
                            'registration_id' => $registration->id,
                            'field_id' => $field->id,
                            'original_name' => $value->getClientOriginalName(),
                            'stored_name' => basename($path),
                            'mime_type' => $value->getClientMimeType(),
                            'extension' => $value->getClientOriginalExtension() ?: $value->guessExtension() ?: '',
                            'size' => $value->getSize(),
                            'path' => $path,
                            'verification_status' => 'pending',
                        ]);
                    }
                } else {
                    if ($value !== null) {
                        $valData = [
                            'registration_id' => $registration->id,
                            'field_id' => $field->id,
                        ];

                        switch ($field->type) {
                            case 'number':
                                $valData['value_number'] = floatval($value);
                                break;
                            case 'date':
                                $valData['value_date'] = $value;
                                break;
                            case 'checkbox':
                                $valData['value_text'] = json_encode((array) $value);
                                break;
                            case 'boolean':
                                $valData['value_boolean'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                break;
                            default:
                                $valData['value_text'] = is_array($value) ? json_encode($value) : strval($value);
                                break;
                        }

                        RegistrationValue::create($valData);
                    }
                }
            }

            $this->systemService->logAudit('ppdb.registration.submit', $registration, null, [
                'registration_number' => $registration->registration_number,
                'track' => $track->name,
            ]);

            return $registration;
        });
    }
}
