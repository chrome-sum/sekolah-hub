<?php

declare(strict_types=1);

namespace App\Modules\Theme\database\seeders;

use App\Modules\System\Models\Setting;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'theme.homepage_sections' => [
                'value' => '["hero", "announcement", "news", "gallery", "ppdb", "contact", "cta"]',
                'description' => 'Susunan layout dan visibilitas section beranda sekolah.',
            ],
            // Hero section config
            'theme.school-classic.hero_title' => [
                'value' => 'Selamat Datang di Sekolah Hub',
                'description' => 'Judul utama pada bagian Hero beranda.',
            ],
            'theme.school-classic.hero_subtitle' => [
                'value' => 'Membentuk generasi unggul yang cerdas, berkarakter, dan siap menghadapi masa depan berteknologi.',
                'description' => 'Sub-judul atau deskripsi singkat pada bagian Hero.',
            ],
            'theme.school-classic.hero_cta_text' => [
                'value' => 'Daftar PPDB Online',
                'description' => 'Label tombol aksi utama pada Hero.',
            ],
            'theme.school-classic.hero_cta_url' => [
                'value' => '/ppdb',
                'description' => 'Link tujuan tombol aksi utama pada Hero.',
            ],
            'theme.school-classic.hero_bg_media_id' => [
                'value' => '',
                'description' => 'ID Media gambar latar belakang Hero.',
            ],
            // Announcement (Headmaster Speech) config
            'theme.school-classic.announcement_title' => [
                'value' => 'Sambutan Kepala Sekolah',
                'description' => 'Judul bagian sambutan kepala sekolah.',
            ],
            'theme.school-classic.announcement_content' => [
                'value' => 'Selamat datang di website resmi Sekolah Hub. Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi demi membina potensi unggul setiap peserta didik. Melalui kurikulum terintegrasi dan lingkungan belajar yang suportif, kami mempersiapkan generasi penerus bangsa yang beriman, bertakwa, berkarakter, dan berdaya saing global.',
                'description' => 'Konten teks sambutan kepala sekolah.',
            ],
            'theme.school-classic.announcement_author' => [
                'value' => 'Drs. Hasbi Al-Aziz, M.Pd.',
                'description' => 'Nama Kepala Sekolah.',
            ],
            'theme.school-classic.announcement_author_role' => [
                'value' => 'Kepala Sekolah Hub',
                'description' => 'Jabatan/Peran penandatangan sambutan.',
            ],
            'theme.school-classic.announcement_image_media_id' => [
                'value' => '',
                'description' => 'ID Media foto kepala sekolah.',
            ],
            // CTA middle banner config
            'theme.school-classic.cta_title' => [
                'value' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Telah Dibuka!',
                'description' => 'Judul pada banner ajakan PPDB.',
            ],
            'theme.school-classic.cta_button_text' => [
                'value' => 'Info Jalur & Formulir Pendaftaran',
                'description' => 'Label tombol aksi banner PPDB.',
            ],
            'theme.school-classic.cta_button_url' => [
                'value' => '/ppdb',
                'description' => 'Link tujuan tombol aksi banner PPDB.',
            ],
            // Contact section details
            'theme.school-classic.contact_title' => [
                'value' => 'Hubungi Kami',
                'description' => 'Judul bagian kontak.',
            ],
            'theme.school-classic.contact_subtitle' => [
                'value' => 'Ada pertanyaan mengenai pendaftaran atau informasi sekolah lainnya? Hubungi kami langsung.',
                'description' => 'Sub-judul bagian kontak.',
            ],
            'theme.school-classic.contact_address' => [
                'value' => 'Jl. Pendidikan No. 12, Kel. Harapan, Kec. Kemajuan, Jakarta Selatan',
                'description' => 'Alamat sekolah.',
            ],
            'theme.school-classic.contact_phone' => [
                'value' => '+62 21-789-0123',
                'description' => 'Nomor telepon resmi sekolah.',
            ],
            'theme.school-classic.contact_email' => [
                'value' => 'info@sekolahhub.sch.id',
                'description' => 'Alamat email resmi sekolah.',
            ],
            'theme.school-classic.contact_maps_embed' => [
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.3051187495514!2d106.82444391476916!3d-6.223456795494916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e49df9c4d9%3A0x6b245ab75775f0a0!2sMega%20Kuningan!5e0!3m2!1sid!2sid!4v1655000000000!5m2!1sid!2sid',
                'description' => 'Link embed Google Maps alamat sekolah.',
            ],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'description' => $data['description'],
                ]
            );
        }
    }
}
