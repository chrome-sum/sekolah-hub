<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Contracts;

use App\Modules\Gallery\Models\GalleryAlbum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface GalleryServiceInterface
{
    /**
     * Get paginated published albums for public view.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPublishedAlbums(int $perPage = 12): LengthAwarePaginator;

    /**
     * Get published album by slug for public view.
     *
     * @param string $slug
     * @return GalleryAlbum
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getAlbumBySlug(string $slug): GalleryAlbum;

    /**
     * Create a new gallery album.
     *
     * @param array $data
     * @return GalleryAlbum
     */
    public function createAlbum(array $data): GalleryAlbum;

    /**
     * Update an existing gallery album.
     *
     * @param GalleryAlbum $album
     * @param array $data
     * @return GalleryAlbum
     */
    public function updateAlbum(GalleryAlbum $album, array $data): GalleryAlbum;

    /**
     * Delete a gallery album.
     *
     * @param GalleryAlbum $album
     * @return void
     */
    public function deleteAlbum(GalleryAlbum $album): void;
}
