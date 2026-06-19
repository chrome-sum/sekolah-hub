<!-- News Section -->
<section id="news" class="bg-gray-50 py-20 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-primary mb-3">
                    SEKILAS INFO
                </span>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Berita & Kegiatan Terbaru
                </h2>
            </div>
        </div>

        @if($posts->isEmpty())
            <div class="bg-white p-12 rounded-2xl text-center border border-gray-100 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2v3m2-3V9m-2 4h.01M17 16h.01" />
                </svg>
                <p class="text-gray-500 font-medium">Belum ada berita yang diterbitkan saat ini.</p>
            </div>
        @else
            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between overflow-hidden">
                        <!-- Media frame -->
                        <div class="aspect-video w-full bg-gray-100 overflow-hidden relative">
                            @if($post->featured_image_url)
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-primary/5 text-primary/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div class="space-y-3">
                                <!-- Date & Author -->
                                <div class="flex items-center text-xs text-gray-400 gap-2">
                                    <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                    <span>&bull;</span>
                                    <span class="truncate">Oleh: {{ $post->author->name ?? 'Admin' }}</span>
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 text-lg leading-snug group-hover:text-primary transition duration-200 line-clamp-2">
                                    <a href="{{ route('public.posts.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-sm text-gray-500 line-clamp-3">
                                    {{ $post->excerpt ?: strip_tags($post->content) }}
                                </p>
                            </div>

                            <!-- Read link -->
                            <div class="pt-6">
                                <a href="{{ route('public.posts.show', $post->slug) }}" class="text-xs font-bold text-primary hover:text-primary-focus inline-flex items-center gap-1 group/link">
                                    Baca Selengkapnya
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transform group-hover/link:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
