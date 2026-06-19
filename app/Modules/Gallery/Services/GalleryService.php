<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Services;

use App\Modules\Gallery\Contracts\GalleryServiceInterface;
use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\Gallery\Actions\CreateAlbumAction;
use App\Modules\Gallery\Actions\UpdateAlbumAction;
use App\Modules\Gallery\Actions\DeleteAlbumAction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GalleryService implements GalleryServiceInterface
{
    public function __construct(
        protected CreateAlbumAction $createAlbumAction,
        protected UpdateAlbumAction $updateAlbumAction,
        protected DeleteAlbumAction $deleteAlbumAction
    ) {}

    /**
     * @inheritDoc
     */
    public function getPublishedAlbums(int $perPage = 12): LengthAwarePaginator
    {
        return GalleryAlbum::where('status', 'published')
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getAlbumBySlug(string $slug): GalleryAlbum
    {
        return GalleryAlbum::where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function createAlbum(array $data): GalleryAlbum
    {
        return $this->createAlbumAction->execute($data);
    }

    /**
     * @inheritDoc
     */
    public function updateAlbum(GalleryAlbum $album, array $data): GalleryAlbum
    {
        return $this->updateAlbumAction->execute($album, $data);
    }

    /**
     * @inheritDoc
     */
    public function deleteAlbum(GalleryAlbum $album): void
    {
        $this->deleteAlbumAction->execute($album);
    }
}
