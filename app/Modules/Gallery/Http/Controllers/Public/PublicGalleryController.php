<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Contracts\GalleryServiceInterface;
use Illuminate\View\View;

class PublicGalleryController extends Controller
{
    public function __construct(
        private GalleryServiceInterface $galleryService
    ) {}

    public function index(): View
    {
        $albums = $this->galleryService->getPublishedAlbums(12);

        return view('gallery::public.index', compact('albums'));
    }

    public function show(string $slug): View
    {
        $album = $this->galleryService->getAlbumBySlug($slug);
        
        $album->load(['items']);

        return view('gallery::public.show', compact('album'));
    }
}
