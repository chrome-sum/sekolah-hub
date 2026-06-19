<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Actions;

use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class UpdateAcademicYearAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(AcademicYear $academicYear, array $data): AcademicYear
    {
        return DB::transaction(function () use ($academicYear, $data) {
            $oldValues = [
                'name' => $academicYear->name,
                'code' => $academicYear->code,
                'is_active' => $academicYear->is_active,
            ];

            $isActive = !empty($data['is_active']);

            if ($isActive) {
                // Deactivate other years
                AcademicYear::query()->where('id', '!=', $academicYear->id)->update(['is_active' => false]);
            }

            $academicYear->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'is_active' => $isActive,
                'registration_open_at' => !empty($data['registration_open_at']) ? $data['registration_open_at'] : null,
                'registration_close_at' => !empty($data['registration_close_at']) ? $data['registration_close_at'] : null,
                'announcement_at' => !empty($data['announcement_at']) ? $data['announcement_at'] : null,
                'updated_by' => auth()->id(),
            ]);

            $this->systemService->logAudit('ppdb.academicyear.update', $academicYear, $oldValues, [
                'name' => $academicYear->name,
                'code' => $academicYear->code,
                'is_active' => $academicYear->is_active,
            ]);

            return $academicYear;
        });
    }
}
