<!-- News Section -->
<section id="news" class="bg-slate-50/50 py-24 md:py-32 border-b border-slate-100 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200/60 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-700">
                        Berita & Kegiatan
                    </span>
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-none">
                    Kabar Terbaru Sekolah
                </h2>
                <p class="text-sm text-gray-500 mt-2">Ikuti artikel berita, pengumuman, dan agenda kegiatan ter-update.</p>
            </div>
        </div>

        @if($posts->isEmpty())
            <!-- Double-Bezel for Empty State -->
            <div class="bg-gray-100/50 p-2 rounded-[2rem] border border-gray-200/50 max-w-lg mx-auto">
                <div class="bg-white p-12 rounded-[calc(2rem-0.5rem)] text-center shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2v3m2-3V9m-2 4h.01M17 16h.01" />
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada berita yang diterbitkan saat ini.</p>
                </div>
            </div>
        @else
            <!-- Grid with Asymmetric Bento style hints or just beautiful layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <!-- Double-Bezel Card -->
                    <div class="group bg-gray-100/60 p-2 rounded-[2rem] border border-gray-200/40 hover:border-gray-300 hover:bg-gray-150/50 hover:-translate-y-1 hover:shadow-md transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] flex flex-col">
                        <div class="bg-white rounded-[calc(2rem-0.5rem)] overflow-hidden shadow-[0_2px_8px_rgba(0,0,0,0.02),inset_0_1px_1px_rgba(255,255,255,0.9)] flex-grow flex flex-col justify-between">
                            <!-- Image Frame with hover-zoom -->
                            <div class="aspect-[16/10] w-full bg-slate-50 overflow-hidden relative border-b border-slate-100">
                                @if($post->featured_image_url)
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary/5 text-primary/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-primary/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                                <div class="space-y-2.5">
                                    <!-- Date & Author info in tabular text -->
                                    <div class="flex items-center text-[11px] text-gray-400 font-mono tracking-tight gap-2">
                                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                        <span>&bull;</span>
                                        <span class="truncate">Oleh: {{ $post->author->name ?? 'Admin' }}</span>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-primary transition-colors duration-300 line-clamp-2">
                                        <a href="{{ route('public.posts.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                    <!-- Excerpt -->
                                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                                        {{ $post->excerpt ?: strip_tags($post->content) }}
                                    </p>
                                </div>

                                <!-- Read Link -->
                                <div class="pt-4 border-t border-gray-50">
                                    <a href="{{ route('public.posts.show', $post->slug) }}" class="text-xs font-bold text-primary inline-flex items-center gap-1 group/link hover:underline">
                                        Baca Selengkapnya
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transform group-hover/link:translate-x-0.5 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
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
