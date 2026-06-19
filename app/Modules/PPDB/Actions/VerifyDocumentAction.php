<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Actions;

use App\Modules\PPDB\Models\RegistrationDocument;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class VerifyDocumentAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(RegistrationDocument $document, string $status, ?string $notes = null): RegistrationDocument
    {
        return DB::transaction(function () use ($document, $status, $notes) {
            $oldValues = [
                'verification_status' => $document->verification_status,
                'verification_notes' => $document->verification_notes,
            ];

            $document->update([
                'verification_status' => $status,
                'verification_notes' => $notes,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $this->systemService->logAudit('ppdb.document.verify', $document, $oldValues, [
                'verification_status' => $document->verification_status,
                'verification_notes' => $document->verification_notes,
            ]);

            return $document;
        });
    }
}
