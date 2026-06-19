<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\Gallery\Contracts\GalleryServiceInterface;
use App\Modules\Gallery\Http\Requests\StoreAlbumRequest;
use App\Modules\Gallery\Http\Requests\UpdateAlbumRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(
        private GalleryServiceInterface $galleryService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', GalleryAlbum::class);

        $query = GalleryAlbum::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $albums = $query->latest()->paginate(10)->withQueryString();

        return view('gallery::admin.index', compact('albums'));
    }

    public function create(): View
    {
        Gate::authorize('create', GalleryAlbum::class);

        $mediaList = \App\Modules\Media\Models\Media::latest()->get();

        return view('gallery::admin.create', compact('mediaList'));
    }

    public function store(StoreAlbumRequest $request): RedirectResponse
    {
        $this->galleryService->createAlbum($request->validated());

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album galeri berhasil dibuat.');
    }

    public function edit(GalleryAlbum $gallery): View
    {
        Gate::authorize('update', $gallery);

        // Load items with sort order
        $gallery->load(['items']);

        $mediaList = \App\Modules\Media\Models\Media::latest()->get();

        return view('gallery::admin.edit', [
            'album' => $gallery,
            'mediaList' => $mediaList,
        ]);
    }

    public function update(UpdateAlbumRequest $request, GalleryAlbum $gallery): RedirectResponse
    {
        Gate::authorize('update', $gallery);

        $this->galleryService->updateAlbum($gallery, $request->validated());

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album galeri berhasil diperbarui.');
    }

    public function destroy(GalleryAlbum $gallery): RedirectResponse
    {
        Gate::authorize('delete', $gallery);

        $this->galleryService->deleteAlbum($gallery);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album galeri berhasil dihapus.');
    }
}
