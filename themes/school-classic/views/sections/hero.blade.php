@php
    $heroBgUrl = null;
    if (!empty($themeSettings['hero_bg_media_id'])) {
        try {
            $heroBgUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl((int) $themeSettings['hero_bg_media_id']);
        } catch (\Exception $e) {}
    }
@endphp

<!-- Hero Section -->
<section class="relative min-h-[70vh] md:min-h-[80vh] flex items-center justify-center bg-zinc-900 overflow-hidden py-16 md:py-24">
    <!-- Background Image -->
    @if($heroBgUrl)
        <img src="{{ $heroBgUrl }}" alt="Hero Background" class="absolute inset-0 w-full h-full object-cover opacity-40">
    @else
        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1920&auto=format&fit=crop" alt="Default Hero Background" class="absolute inset-0 w-full h-full object-cover opacity-30">
    @endif

    <!-- Overlay Gradient -->
    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-900/80 to-zinc-900/40"></div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full z-10">
        <div class="max-w-3xl">
            <!-- Eyebrow -->
            <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-primary mb-4">
                Sambutan Hangat & Pendaftaran
            </span>

            <!-- Headline (max 2 lines) -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold tracking-tight text-white leading-none mb-6">
                {{ $themeSettings['hero_title'] ?? 'Selamat Datang di Sekolah Hub' }}
            </h1>

            <!-- Subtext (max 20 words) -->
            <p class="text-lg md:text-xl text-zinc-300 leading-relaxed mb-10 max-w-2xl">
                {{ $themeSettings['hero_subtitle'] ?? 'Membentuk generasi unggul yang cerdas, berkarakter, dan siap menghadapi masa depan berteknologi.' }}
            </p>

            <!-- CTAs -->
            <div class="flex flex-wrap gap-4 items-center">
                @if(!empty($themeSettings['hero_cta_text']))
                    <a href="{{ $themeSettings['hero_cta_url'] ?? '/ppdb' }}" class="btn btn-primary rounded-lg shadow-lg hover:-translate-y-[1px] transition active:scale-[0.98]">
                        {{ $themeSettings['hero_cta_text'] }}
                    </a>
                @endif
                <a href="#announcement" class="btn btn-outline text-white border-zinc-700 hover:bg-zinc-800 hover:border-zinc-600 rounded-lg transition">
                    Sambutan Sekolah
                </a>
            </div>
        </div>
    </div>
</section>
