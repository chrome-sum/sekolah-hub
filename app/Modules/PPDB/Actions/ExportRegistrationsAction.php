<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Actions;

use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Support\RegistrationsExport;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ExportRegistrationsAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(AdmissionTrack $track)
    {
        $filename = 'pendaftar-' . Str::slug($track->name) . '-' . date('YmdHis') . '.xlsx';

        $this->systemService->logAudit('ppdb.registrations.export', $track, null, [
            'track' => $track->name,
            'filename' => $filename,
        ]);

        return Excel::download(new RegistrationsExport($track), $filename);
    }
}
