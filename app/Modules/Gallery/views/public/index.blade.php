<x-public-layout>
    @section('title', 'Galeri Foto - ' . config('app.name', 'Sekolah Hub'))

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Galeri Foto</h1>
            <p class="text-gray-500 max-w-2xl">Dokumentasi foto berbagai kegiatan dan peristiwa penting di sekolah kami.</p>
        </header>

        <!-- Albums Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @forelse($albums as $album)
                <div class="card bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <!-- Album Cover -->
                        @if($album->cover_image_url)
                            <div class="aspect-video bg-gray-50 overflow-hidden border-b border-gray-50 relative">
                                <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}" class="w-full h-full object-cover" />
                                <div class="absolute bottom-2 right-2 badge badge-neutral bg-black/60 text-white border-0 text-xs py-1 px-2.5">
                                    {{ $album->items()->count() }} Foto
                                </div>
                            </div>
                        @else
                            <div class="aspect-video bg-gray-100 flex items-center justify-center border-b border-gray-50 text-gray-300 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <div class="absolute bottom-2 right-2 badge badge-neutral bg-black/60 text-white border-0 text-xs py-1 px-2.5">
                                    {{ $album->items()->count() }} Foto
                                </div>
                            </div>
                        @endif

                        <!-- Card Body -->
                        <div class="p-5">
                            <!-- Title -->
                            <h2 class="text-lg font-bold text-gray-900 hover:text-primary mb-2 line-clamp-1">
                                <a href="{{ route('public.gallery.show', $album->slug) }}">{{ $album->title }}</a>
                            </h2>

                            <!-- Description -->
                            @if($album->description)
                                <p class="text-gray-500 text-sm line-clamp-2">
                                    {{ $album->description }}
                                </p>
                            @else
                                <p class="text-gray-400 text-sm italic">Tidak ada deskripsi album.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-5 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                        <span>{{ $album->published_at ? $album->published_at->format('d M Y') : $album->created_at->format('d M Y') }}</span>
                        <a href="{{ route('public.gallery.show', $album->slug) }}" class="text-primary font-semibold hover:underline">Lihat Foto &rarr;</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400 bg-white border border-dashed rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2v3m2-3V9m0 0a2 2 0 012 2v7a2 2 0 01-2 2h-1m-1 4h.01m-4 0h.01m-3 0h.01m-3 0h.01" /></svg>
                    <p class="text-sm">Belum ada album galeri yang diterbitkan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div>
            {{ $albums->links() }}
        </div>
    </div>
</x-public-layout>
