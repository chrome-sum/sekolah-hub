<x-public-layout>
    @section('title', $album->title . ' - Galeri - ' . config('app.name', 'Sekolah Hub'))

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="galleryLightbox()">
        <!-- Breadcrumbs -->
        <div class="text-sm breadcrumbs mb-6 text-gray-500">
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="{{ route('public.gallery.index') }}">Galeri</a></li>
                <li>{{ $album->title }}</li>
            </ul>
        </div>

        <header class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $album->title }}</h1>
            <div class="flex items-center gap-4 text-xs text-gray-400 mb-4">
                <span>Diterbitkan: {{ $album->published_at ? $album->published_at->format('d M Y') : $album->created_at->format('d M Y') }}</span>
                <span>•</span>
                <span>{{ $album->items->count() }} Foto</span>
            </div>
            @if($album->description)
                <p class="text-gray-600 bg-white p-4 rounded-lg border border-gray-100 shadow-sm leading-relaxed">{{ $album->description }}</p>
            @endif
        </header>

        <!-- Photos Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @forelse($album->items as $index => $item)
                @php
                    $imageUrl = $item->url;
                    $mediumUrl = $item->medium_url ?: $imageUrl;
                    $thumbnailUrl = $item->thumbnail_url ?: $mediumUrl;
                @endphp
                <div class="group relative aspect-square bg-gray-50 rounded-lg overflow-hidden border border-gray-100 shadow-sm cursor-pointer"
                     @click="openLightbox({{ $index }})">
                    <img src="{{ $thumbnailUrl }}" alt="{{ $item->caption ?: $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-3 text-white">
                        @if($item->caption)
                            <p class="text-xs font-medium line-clamp-2">{{ $item->caption }}</p>
                        @else
                            <p class="text-xs font-medium italic">Perbesar Foto</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400 bg-white border border-dashed rounded-xl">
                    <p class="text-sm">Tidak ada foto di dalam album ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Lightbox Modal Overlay -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex flex-col items-center justify-between bg-black/95 py-6 px-4 select-none"
             @keydown.window.escape="close()"
             @keydown.window.arrow-right="next()"
             @keydown.window.arrow-left="prev()"
             style="display: none;">
            
            <!-- Top Controls -->
            <div class="w-full max-w-5xl flex items-center justify-between text-white text-sm">
                <div>
                    <span x-text="(activeIndex + 1)"></span> / <span x-text="items.length"></span>
                </div>
                <button @click="close()" class="btn btn-sm btn-circle btn-ghost text-white hover:bg-white/10">
                    ✕
                </button>
            </div>

            <!-- Main Slide Container -->
            <div class="relative w-full max-w-4xl flex-grow flex items-center justify-center py-4">
                <!-- Navigation Prev -->
                <button @click="prev()" class="absolute left-2 z-10 btn btn-circle btn-ghost text-white bg-black/20 hover:bg-white/20">
                    ❮
                </button>

                <!-- Active Image -->
                <div class="max-h-[70vh] max-w-full flex items-center justify-center">
                    <img :src="items[activeIndex].mediumUrl" 
                         :alt="items[activeIndex].caption"
                         class="max-h-[70vh] max-w-full object-contain rounded shadow-lg" />
                </div>

                <!-- Navigation Next -->
                <button @click="next()" class="absolute right-2 z-10 btn btn-circle btn-ghost text-white bg-black/20 hover:bg-white/20">
                    ❯
                </button>
            </div>

            <!-- Bottom Caption & Description -->
            <div class="w-full max-w-3xl text-center text-white pb-4">
                <template x-if="items[activeIndex].caption">
                    <p class="text-sm md:text-base font-medium px-4 py-2 bg-black/40 rounded-lg inline-block max-w-full" x-text="items[activeIndex].caption"></p>
                </template>
                <template x-if="!items[activeIndex].caption">
                    <p class="text-xs text-gray-400 italic" x-text="albumTitle"></p>
                </template>
            </div>
        </div>
    </div>

    <script>
        function galleryLightbox() {
            return {
                open: false,
                activeIndex: 0,
                albumTitle: '{{ addslashes($album->title) }}',
                
                // Photo Items
                items: [
                    @foreach($album->items as $item)
                    {
                        url: '{{ $item->url }}',
                        mediumUrl: '{{ $item->medium_url ?: $item->url }}',
                        caption: '{{ addslashes($item->caption ?? "") }}'
                    },
                    @endforeach
                ],

                openLightbox(index) {
                    this.activeIndex = index;
                    this.open = true;
                    // Disable body scroll when lightbox is open
                    document.body.classList.add('overflow-hidden');
                },

                close() {
                    this.open = false;
                    // Re-enable body scroll
                    document.body.classList.remove('overflow-hidden');
                },

                next() {
                    if (this.items.length > 0) {
                        this.activeIndex = (this.activeIndex + 1) % this.items.length;
                    }
                },

                prev() {
                    if (this.items.length > 0) {
                        this.activeIndex = (this.activeIndex - 1 + this.items.length) % this.items.length;
                    }
                }
            };
        }
    </script>
</x-public-layout>
