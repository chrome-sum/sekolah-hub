<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Services;

use App\Modules\PPDB\Contracts\PPDBServiceInterface;
use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Models\RegistrationDocument;
use App\Modules\PPDB\Actions\CreateAcademicYearAction;
use App\Modules\PPDB\Actions\UpdateAcademicYearAction;
use App\Modules\PPDB\Actions\SubmitRegistrationAction;
use App\Modules\PPDB\Actions\UpdateRegistrationStatusAction;
use App\Modules\PPDB\Actions\VerifyDocumentAction;
use App\Modules\PPDB\Actions\ExportRegistrationsAction;

class PPDBService implements PPDBServiceInterface
{
    public function __construct(
        private CreateAcademicYearAction $createAcademicYearAction,
        private UpdateAcademicYearAction $updateAcademicYearAction,
        private SubmitRegistrationAction $submitRegistrationAction,
        private UpdateRegistrationStatusAction $updateRegistrationStatusAction,
        private VerifyDocumentAction $verifyDocumentAction,
        private ExportRegistrationsAction $exportRegistrationsAction
    ) {}

    public function createAcademicYear(array $data): AcademicYear
    {
        return $this->createAcademicYearAction->execute($data);
    }

    public function updateAcademicYear(AcademicYear $academicYear, array $data): AcademicYear
    {
        return $this->updateAcademicYearAction->execute($academicYear, $data);
    }

    public function submitRegistration(array $data): Registration
    {
        return $this->submitRegistrationAction->execute($data);
    }

    public function updateRegistrationStatus(Registration $registration, string $status, ?string $notes = null): Registration
    {
        return $this->updateRegistrationStatusAction->execute($registration, $status, $notes);
    }

    public function verifyDocument(RegistrationDocument $document, string $status, ?string $notes = null): RegistrationDocument
    {
        return $this->verifyDocumentAction->execute($document, $status, $notes);
    }

    public function exportRegistrations(AdmissionTrack $track)
    {
        return $this->exportRegistrationsAction->execute($track);
    }

    public function getActiveAcademicYear(): ?AcademicYear
    {
        return AcademicYear::where('is_active', true)->first();
    }

    public function getActiveTracks(int $academicYearId): \Illuminate\Support\Collection
    {
        return AdmissionTrack::where('academic_year_id', $academicYearId)
            ->where('is_active', true)
            ->get();
    }
}
