<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Actions;

use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class DeleteAlbumAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(GalleryAlbum $album): void
    {
        DB::transaction(function () use ($album) {
            $oldValues = [
                'title' => $album->title,
                'slug' => $album->slug,
                'status' => $album->status,
            ];

            $album->delete();

            // Log Audit
            $this->systemService->logAudit('gallery.album.delete', $album, $oldValues, null);
        });
    }
}
