<?php

declare(strict_types=1);

namespace App\Modules\Contact\database\seeders;

use App\Modules\Contact\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Unread Message
        ContactMessage::firstOrCreate([
            'email' => 'budi.santoso@gmail.com',
            'subject' => 'Pertanyaan Mengenai Biaya Pendaftaran PPDB',
        ], [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'message' => "Halo Admin, saya ingin menanyakan rincian biaya pendaftaran untuk PPDB TA 2026/2027 jalur prestasi. Apakah ada diskon biaya bagi peraih juara umum tingkat kabupaten? Terima kasih.",
            'status' => 'unread',
            'ip_address' => '192.168.1.10',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        // 2. Read Message
        ContactMessage::firstOrCreate([
            'email' => 'siti.aminah@gmail.com',
            'subject' => 'Undangan Kerja Sama Seminar Kesehatan Remaja',
        ], [
            'name' => 'Siti Aminah',
            'phone' => '085712345678',
            'message' => "Kepada Yth. Kepala Sekolah Sekolah Hub, kami dari Puskesmas Kecamatan ingin menawarkan program seminar kesehatan remaja dan pencegahan stunting untuk siswa kelas 10-12 secara gratis. Mohon informasinya bila sekolah bersedia.",
            'status' => 'read',
            'ip_address' => '192.168.1.11',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
        ]);

        // 3. Replied Message
        ContactMessage::firstOrCreate([
            'email' => 'ahmad.subarjo@yahoo.com',
            'subject' => 'Laporan Kerusakan Fasilitas Lapangan Basket',
        ], [
            'name' => 'Ahmad Subarjo',
            'phone' => null,
            'message' => "Selamat siang, saya orang tua murid ingin melaporkan bahwa ring basket di lapangan utama dalam kondisi goyang dan berbahaya bagi anak-anak saat bermain. Mohon ditindaklanjuti demi keselamatan siswa.",
            'status' => 'replied',
            'replied_at' => now()->subDay(),
            'ip_address' => '192.168.1.12',
            'user_agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36',
        ]);

        // 4. Archived Message
        ContactMessage::firstOrCreate([
            'email' => 'spammer.guy@gmail.com',
            'subject' => 'Tawaran Jasa Pembuatan Website Murah',
        ], [
            'name' => 'Spam Bot',
            'phone' => '021-999-999',
            'message' => "Promo pembuatan website sekolah profesional hanya 500rb rupiah. Hubungi nomor kami segera di 0888-8888-888.",
            'status' => 'archived',
            'ip_address' => '203.0.113.1',
            'user_agent' => 'Python-urllib/3.8',
        ]);
    }
}
