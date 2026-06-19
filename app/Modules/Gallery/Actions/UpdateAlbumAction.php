<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Actions;

use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateAlbumAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(GalleryAlbum $album, array $data): GalleryAlbum
    {
        return DB::transaction(function () use ($album, $data) {
            $oldValues = [
                'title' => $album->title,
                'slug' => $album->slug,
                'status' => $album->status,
            ];

            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);

            // Handle unique slug
            if ($slug !== $album->slug) {
                $originalSlug = $slug;
                $count = 1;
                while (GalleryAlbum::where('slug', $slug)->where('id', '!=', $album->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }
            }

            $publishedAt = $album->published_at;
            if (isset($data['status'])) {
                if ($data['status'] === 'published' && $album->status !== 'published') {
                    $publishedAt = now();
                } elseif ($data['status'] !== 'published') {
                    $publishedAt = null;
                }
            }

            $album->update([
                'title' => $data['title'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'cover_media_id' => !empty($data['cover_media_id']) ? (int) $data['cover_media_id'] : null,
                'status' => $data['status'] ?? $album->status,
                'published_at' => $publishedAt,
                'updated_by' => auth()->id(),
            ]);

            // Sync Album Items if present in request data
            if (isset($data['items'])) {
                // Delete existing items
                $album->items()->delete();

                // Insert new items
                $itemsToInsert = [];
                foreach ($data['items'] as $item) {
                    $itemsToInsert[] = [
                        'album_id' => $album->id,
                        'media_id' => (int) $item['media_id'],
                        'caption' => $item['caption'] ?? null,
                        'sort_order' => (int) ($item['sort_order'] ?? 0),
                        'created_at' => now(),
                    ];
                }

                if (!empty($itemsToInsert)) {
                    $album->items()->createMany($itemsToInsert);
                }
            }

            // Log Audit
            $this->systemService->logAudit('gallery.album.update', $album, $oldValues, [
                'title' => $album->title,
                'slug' => $album->slug,
                'status' => $album->status,
            ]);

            return $album;
        });
    }
}
