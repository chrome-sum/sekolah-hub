<?php

declare(strict_types=1);

namespace App\Modules\PPDB\database\seeders;

use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PPDBSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Academic Year
        $academicYear = AcademicYear::firstOrCreate(
            ['code' => '2026/2027'],
            [
                'name' => 'Tahun Ajaran 2026/2027',
                'is_active' => true,
                'registration_open_at' => now()->subMonth(),
                'registration_close_at' => now()->addMonth(),
                'announcement_at' => now()->addMonth()->addDays(10),
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        // 2. Seed Tracks
        $tracks = [
            [
                'name' => 'Zonasi',
                'quota' => 100,
                'description' => 'Penerimaan peserta didik berdasarkan zonasi wilayah tempat tinggal.',
            ],
            [
                'name' => 'Prestasi',
                'quota' => 50,
                'description' => 'Penerimaan peserta didik berdasarkan nilai rapor dan sertifikat penghargaan.',
            ],
            [
                'name' => 'Afirmasi',
                'quota' => 30,
                'description' => 'Penerimaan peserta didik dari keluarga tidak mampu atau memiliki KIP.',
            ]
        ];

        $trackModels = [];
        foreach ($tracks as $t) {
            $slug = Str::slug($t['name']);
            $trackModels[$t['name']] = AdmissionTrack::firstOrCreate(
                ['academic_year_id' => $academicYear->id, 'slug' => $slug],
                [
                    'name' => $t['name'],
                    'quota' => $t['quota'],
                    'description' => $t['description'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Seed Standard Form Fields for all tracks
        $standardFields = [
            [
                'field_key' => 'nama_lengkap',
                'label' => 'Nama Lengkap',
                'type' => 'text',
                'placeholder' => 'Masukkan nama lengkap sesuai ijazah',
                'help_text' => 'Nama lengkap beserta gelar jika ada.',
                'is_required' => true,
                'validation_rules' => 'required|string|max:255',
                'sort_order' => 1,
            ],
            [
                'field_key' => 'nisn',
                'label' => 'NISN',
                'type' => 'number',
                'placeholder' => 'Masukkan 10 digit NISN',
                'help_text' => 'Nomor Induk Siswa Nasional.',
                'is_required' => true,
                'validation_rules' => 'required|numeric|digits:10',
                'sort_order' => 2,
            ],
            [
                'field_key' => 'alamat_rumah',
                'label' => 'Alamat Rumah',
                'type' => 'textarea',
                'placeholder' => 'Masukkan alamat rumah lengkap',
                'help_text' => 'RT, RW, Desa/Kelurahan, Kecamatan, Kota/Kabupaten.',
                'is_required' => true,
                'validation_rules' => 'required|string|max:1000',
                'sort_order' => 3,
            ],
            [
                'field_key' => 'asal_sekolah',
                'label' => 'Asal Sekolah',
                'type' => 'text',
                'placeholder' => 'Nama SMP / MTs asal',
                'help_text' => 'Nama sekolah jenjang sebelumnya.',
                'is_required' => true,
                'validation_rules' => 'required|string|max:255',
                'sort_order' => 4,
            ],
            [
                'field_key' => 'jenis_kelamin',
                'label' => 'Jenis Kelamin',
                'type' => 'radio',
                'options' => ['Laki-laki', 'Perempuan'],
                'help_text' => 'Pilih jenis kelamin.',
                'is_required' => true,
                'validation_rules' => 'required|string|in:Laki-laki,Perempuan',
                'sort_order' => 5,
            ],
            [
                'field_key' => 'dokumen_ijazah',
                'label' => 'Upload Ijazah/SKL',
                'type' => 'file',
                'help_text' => 'Format PDF/JPG/PNG maksimal 2MB.',
                'is_required' => true,
                'validation_rules' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'sort_order' => 6,
            ],
        ];

        foreach ($trackModels as $name => $track) {
            // Seed standard fields
            foreach ($standardFields as $field) {
                AdmissionFormField::firstOrCreate(
                    ['track_id' => $track->id, 'field_key' => $field['field_key']],
                    [
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'placeholder' => $field['placeholder'] ?? null,
                        'help_text' => $field['help_text'] ?? null,
                        'is_required' => $field['is_required'],
                        'options' => $field['options'] ?? null,
                        'validation_rules' => $field['validation_rules'] ?? null,
                        'sort_order' => $field['sort_order'],
                        'is_active' => true,
                    ]
                );
            }

            // Seed track-specific fields
            if ($name === 'Prestasi') {
                AdmissionFormField::firstOrCreate(
                    ['track_id' => $track->id, 'field_key' => 'nilai_rapor'],
                    [
                        'label' => 'Rata-rata Nilai Rapor',
                        'type' => 'number',
                        'placeholder' => 'Contoh: 85.5',
                        'help_text' => 'Rata-rata nilai rapor semester 1-5.',
                        'is_required' => true,
                        'validation_rules' => 'required|numeric|between:0,100',
                        'sort_order' => 7,
                        'is_active' => true,
                    ]
                );

                AdmissionFormField::firstOrCreate(
                    ['track_id' => $track->id, 'field_key' => 'sertifikat_prestasi'],
                    [
                        'label' => 'Sertifikat Prestasi',
                        'type' => 'file',
                        'help_text' => 'Format PDF/JPG/PNG max 2MB (Opsional).',
                        'is_required' => false,
                        'validation_rules' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                        'sort_order' => 8,
                        'is_active' => true,
                    ]
                );
            } elseif ($name === 'Afirmasi') {
                AdmissionFormField::firstOrCreate(
                    ['track_id' => $track->id, 'field_key' => 'nomor_kip'],
                    [
                        'label' => 'Nomor KIP',
                        'type' => 'text',
                        'placeholder' => 'Masukkan nomor Kartu Indonesia Pintar',
                        'help_text' => 'Kosongkan jika bukan penerima KIP.',
                        'is_required' => true,
                        'validation_rules' => 'required|string|max:50',
                        'sort_order' => 7,
                        'is_active' => true,
                    ]
                );

                AdmissionFormField::firstOrCreate(
                    ['track_id' => $track->id, 'field_key' => 'kartu_kip'],
                    [
                        'label' => 'Upload Kartu KIP',
                        'type' => 'file',
                        'help_text' => 'Format PDF/JPG/PNG max 2MB.',
                        'is_required' => true,
                        'validation_rules' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                        'sort_order' => 8,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
