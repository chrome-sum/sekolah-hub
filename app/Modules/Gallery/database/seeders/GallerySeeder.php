<?php

declare(strict_types=1);

namespace App\Modules\Gallery\database\seeders;

use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\Gallery\Models\GalleryAlbumItem;
use App\Modules\Media\Models\Media;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Find or create dummy media items first
        $mediaList = Media::take(4)->get();
        if ($mediaList->isEmpty()) {
            // Seed a few dummy media records
            for ($i = 1; $i <= 4; $i++) {
                $mediaList->push(Media::create([
                    'disk' => 'public',
                    'path' => "media/dummy_photo_{$i}.jpg",
                    'filename' => "dummy_photo_{$i}.jpg",
                    'original_name' => "Photo {$i}.jpg",
                    'extension' => 'jpg',
                    'mime_type' => 'image/jpeg',
                    'size' => 1024 * 100 * $i,
                    'width' => 800,
                    'height' => 600,
                    'alt_text' => "Foto Kegiatan Sekolah {$i}",
                    'caption' => "Deskripsi foto kegiatan {$i}",
                ]));
            }
        }

        // 1. Create Published Album
        $album1 = GalleryAlbum::firstOrCreate([
            'slug' => 'kegiatan-classmeet-2026'
        ], [
            'title' => 'Kegiatan Classmeet 2026',
            'description' => 'Dokumentasi berbagai pertandingan olahraga dan seni antar kelas yang diselenggarakan di akhir semester ganjil tahun 2026.',
            'cover_media_id' => $mediaList->first()?->id,
            'status' => 'published',
            'published_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Link photos to Album 1
        foreach ($mediaList as $index => $media) {
            GalleryAlbumItem::firstOrCreate([
                'album_id' => $album1->id,
                'media_id' => $media->id,
            ], [
                'caption' => "Keseruan lomba hari ke-" . ($index + 1),
                'sort_order' => $index,
            ]);
        }

        // 2. Create Draft Album
        GalleryAlbum::firstOrCreate([
            'slug' => 'pembangunan-laboratorium-bahasa'
        ], [
            'title' => 'Progres Pembangunan Laboratorium Bahasa',
            'description' => 'Foto-foto perkembangan konstruksi laboratorium bahasa baru sekolah dari awal pondasi hingga tahap akhir.',
            'cover_media_id' => $mediaList->last()?->id,
            'status' => 'draft',
            'published_at' => null,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
