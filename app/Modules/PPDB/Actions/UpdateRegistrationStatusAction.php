<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Actions;

use App\Modules\PPDB\Models\Registration;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class UpdateRegistrationStatusAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(Registration $registration, string $status, ?string $notes = null): Registration
    {
        return DB::transaction(function () use ($registration, $status, $notes) {
            $oldValues = [
                'status' => $registration->status,
                'notes' => $registration->notes,
            ];

            $updates = [
                'status' => $status,
            ];

            if ($notes !== null) {
                $updates['notes'] = $notes;
            }

            if ($status === 'accepted') {
                $updates['accepted_at'] = now();
                $updates['rejected_at'] = null;
            } elseif ($status === 'rejected') {
                $updates['rejected_at'] = now();
                $updates['accepted_at'] = null;
            } elseif ($status === 'verified') {
                $updates['verified_at'] = now();
            }

            if ($status === 'submitted') {
                $updates['submitted_at'] = now();
            }

            $registration->update($updates);

            $this->systemService->logAudit('ppdb.registration.update_status', $registration, $oldValues, [
                'status' => $registration->status,
                'notes' => $registration->notes,
            ]);

            return $registration;
        });
    }
}
