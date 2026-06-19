<!-- Gallery Section -->
<section id="gallery" class="bg-white py-24 md:py-32 border-b border-slate-100 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16 gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200/60 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-700">
                        Dokumentasi
                    </span>
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-none">
                    Galeri & Foto Kegiatan
                </h2>
                <p class="text-sm text-gray-500 mt-2">Momen-momen penting dan aktivitas siswa di lingkungan sekolah.</p>
            </div>
            <div>
                <a href="{{ route('public.gallery.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-200 hover:border-gray-400 hover:bg-gray-50 text-gray-600 hover:text-gray-900 font-bold text-xs rounded-full transition-all duration-300 active:scale-[0.98]">
                    Lihat Semua Galeri
                </a>
            </div>
        </div>

        @if($albums->isEmpty())
            <!-- Double-Bezel Empty State -->
            <div class="bg-gray-100/50 p-2 rounded-[2rem] border border-gray-200/50 max-w-lg mx-auto">
                <div class="bg-white p-12 rounded-[calc(2rem-0.5rem)] text-center shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada album dokumentasi saat ini.</p>
                </div>
            </div>
        @else
            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($albums as $album)
                    <!-- Double-Bezel Card -->
                    <div class="group bg-gray-100/60 p-2 rounded-[2rem] border border-gray-200/40 hover:border-gray-300 hover:bg-gray-150/50 hover:-translate-y-1 hover:shadow-md transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] flex flex-col">
                        <div class="bg-white rounded-[calc(2rem-0.5rem)] overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.9)] flex-grow flex flex-col justify-between">
                            <!-- Cover Image Frame with hover zoom -->
                            <div class="aspect-[16/10] bg-slate-50 overflow-hidden relative border-b border-slate-100">
                                @if($album->cover_image_url)
                                    <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Photo Count Badge in sleek transparent dark island -->
                                <div class="absolute bottom-3 right-3 bg-zinc-950/85 backdrop-blur-md text-white border-0 text-[10px] font-bold py-1.5 px-3 rounded-full shadow-sm font-mono tracking-tight">
                                    {{ $album->items()->count() }} FOTO
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-primary transition-colors duration-300 line-clamp-1">
                                        <a href="{{ route('public.gallery.show', $album->slug) }}">
                                            {{ $album->title }}
                                        </a>
                                    </h3>
                                    @if($album->description)
                                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed font-light">{{ $album->description }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 italic font-light">Tidak ada deskripsi album.</p>
                                    @endif
                                </div>

                                <div class="pt-4 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400 mt-4 font-mono tracking-tight">
                                    <span>{{ $album->published_at ? $album->published_at->format('d M Y') : $album->created_at->format('d M Y') }}</span>
                                    <a href="{{ route('public.gallery.show', $album->slug) }}" class="font-bold text-primary hover:text-primary-focus inline-flex items-center gap-0.5 hover:underline">
                                        Lihat Album &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
