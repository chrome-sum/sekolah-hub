<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Support;

use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use App\Modules\PPDB\Models\Registration;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationsExport implements FromArray, WithHeadings
{
    protected AdmissionTrack $track;
    protected $fields;

    public function __construct(AdmissionTrack $track)
    {
        $this->track = $track;
        $this->fields = AdmissionFormField::where('track_id', $track->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'Nomor Pendaftaran',
            'Status',
            'Tanggal Pendaftaran',
        ];

        foreach ($this->fields as $field) {
            $headings[] = $field->label;
        }

        return $headings;
    }

    public function array(): array
    {
        $registrations = Registration::where('track_id', $this->track->id)
            ->with(['values.field', 'documents.field'])
            ->get();

        $rows = [];

        foreach ($registrations as $registration) {
            $row = [
                $registration->registration_number,
                $registration->status,
                $registration->submitted_at ? $registration->submitted_at->format('Y-m-d H:i:s') : '-',
            ];

            foreach ($this->fields as $field) {
                if ($field->type === 'file') {
                    $doc = $registration->documents->firstWhere('field_id', $field->id);
                    $row[] = $doc ? $doc->original_name : '-';
                } else {
                    $valModel = $registration->values->firstWhere('field_id', $field->id);
                    if ($valModel) {
                        $val = $valModel->real_value;
                        if (is_array($val)) {
                            $row[] = implode(', ', $val);
                        } else {
                            $row[] = is_bool($val) ? ($val ? 'Ya' : 'Tidak') : strval($val);
                        }
                    } else {
                        $row[] = '-';
                    }
                }
            }

            $rows[] = $row;
        }

        return $rows;
    }
}
