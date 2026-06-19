<!-- Gallery Section -->
<section id="gallery" class="bg-white py-20 border-b border-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-primary mb-3">
                    DOKUMENTASI
                </span>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Galeri Kegiatan Sekolah
                </h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('public.gallery.index') }}" class="btn btn-outline btn-sm rounded-lg text-gray-600 border-gray-200 hover:bg-gray-50 hover:text-gray-900">
                    Lihat Semua Galeri
                </a>
            </div>
        </div>

        @if($albums->isEmpty())
            <div class="bg-white p-12 rounded-2xl text-center border border-dashed border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 font-medium">Belum ada album dokumentasi saat ini.</p>
            </div>
        @else
            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($albums as $album)
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition duration-300 flex flex-col justify-between">
                        <!-- Cover Image -->
                        <div class="aspect-video bg-gray-150 overflow-hidden relative">
                            @if($album->cover_image_url)
                                <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <!-- Photo Count Badge -->
                            <div class="absolute bottom-3 right-3 badge badge-neutral bg-black/60 text-white border-0 text-[10px] font-semibold py-1.5 px-3 rounded-full">
                                {{ $album->items()->count() }} Foto
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div class="space-y-2">
                                <h3 class="font-bold text-gray-900 text-lg leading-snug group-hover:text-primary transition duration-200 line-clamp-1">
                                    <a href="{{ route('public.gallery.show', $album->slug) }}">
                                        {{ $album->title }}
                                    </a>
                                </h3>
                                @if($album->description)
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $album->description }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Tidak ada deskripsi album.</p>
                                @endif
                            </div>

                            <div class="pt-6 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400 mt-4">
                                <span>{{ $album->published_at ? $album->published_at->format('d M Y') : $album->created_at->format('d M Y') }}</span>
                                <a href="{{ route('public.gallery.show', $album->slug) }}" class="font-bold text-primary hover:text-primary-focus inline-flex items-center gap-1">
                                    Lihat Album &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
