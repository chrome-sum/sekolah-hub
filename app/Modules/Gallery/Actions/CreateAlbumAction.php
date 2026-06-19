<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Actions;

use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateAlbumAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(array $data): GalleryAlbum
    {
        return DB::transaction(function () use ($data) {
            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);

            // Handle unique slug
            $originalSlug = $slug;
            $count = 1;
            while (GalleryAlbum::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            $album = GalleryAlbum::create([
                'title' => $data['title'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'cover_media_id' => !empty($data['cover_media_id']) ? (int) $data['cover_media_id'] : null,
                'status' => $data['status'] ?? 'draft',
                'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Log Audit
            $this->systemService->logAudit('gallery.album.create', $album, null, [
                'title' => $album->title,
                'slug' => $album->slug,
                'status' => $album->status,
            ]);

            return $album;
        });
    }
}
