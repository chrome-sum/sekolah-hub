<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Contracts;

use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Models\RegistrationDocument;

interface PPDBServiceInterface
{
    /**
     * Create a new Academic Year.
     */
    public function createAcademicYear(array $data): AcademicYear;

    /**
     * Update an Academic Year.
     */
    public function updateAcademicYear(AcademicYear $academicYear, array $data): AcademicYear;

    /**
     * Submit a student registration.
     */
    public function submitRegistration(array $data): Registration;

    /**
     * Update student registration status.
     */
    public function updateRegistrationStatus(Registration $registration, string $status, ?string $notes = null): Registration;

    /**
     * Verify a registration document.
     */
    public function verifyDocument(RegistrationDocument $document, string $status, ?string $notes = null): RegistrationDocument;

    /**
     * Export registrations for an admission track to Excel.
     */
    public function exportRegistrations(AdmissionTrack $track);
}
