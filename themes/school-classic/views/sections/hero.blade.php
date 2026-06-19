@php
    $heroBgUrl = null;
    if (!empty($themeSettings['hero_bg_media_id'])) {
        try {
            $heroBgUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl((int) $themeSettings['hero_bg_media_id']);
        } catch (\Exception $e) {}
    }
@endphp

<!-- Hero Section -->
<section class="relative min-h-[85vh] flex items-center justify-center bg-zinc-950 overflow-hidden py-24 md:py-32">
    <!-- Background Image with sophisticated blend -->
    @if($heroBgUrl)
        <img src="{{ $heroBgUrl }}" alt="Hero Background" class="absolute inset-0 w-full h-full object-cover opacity-35 scale-105 filter blur-[1px]">
    @else
        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1920&auto=format&fit=crop" alt="Default Hero Background" class="absolute inset-0 w-full h-full object-cover opacity-25 scale-102">
    @endif

    <!-- Overlay Gradients for Depth -->
    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/80 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-zinc-950/90 via-transparent to-transparent"></div>
    
    <!-- Cinematic Blur Orbs -->
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] pointer-events-none animate-pulse duration-10000"></div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 w-full z-10">
        <div class="max-w-3xl space-y-8">
            <!-- Eyebrow Tag -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-300">
                    Portal Resmi Penerimaan & Informasi
                </span>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.05]">
                {{ $themeSettings['hero_title'] ?? 'Selamat Datang di Sekolah Hub' }}
            </h1>

            <!-- Subtext -->
            <p class="text-lg md:text-xl text-zinc-300 leading-relaxed max-w-2xl font-light">
                {{ $themeSettings['hero_subtitle'] ?? 'Membentuk generasi unggul yang cerdas, berkarakter, dan siap menghadapi masa depan berteknologi.' }}
            </p>

            <!-- CTAs with Asymmetric Pill Buttons -->
            <div class="flex flex-wrap gap-4 items-center pt-4">
                @if(!empty($themeSettings['hero_cta_text']))
                    <a href="{{ $themeSettings['hero_cta_url'] ?? '/ppdb' }}" class="group inline-flex items-center gap-3 bg-primary text-white font-bold text-sm pl-6 pr-3 py-3 rounded-full transition-all duration-300 hover:bg-primary/95 hover:shadow-lg hover:shadow-primary/25 active:scale-[0.98]">
                        <span>{{ $themeSettings['hero_cta_text'] }}</span>
                        <!-- Circle enclosed icon -->
                        <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:translate-x-1 group-hover:-translate-y-[1px] transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </a>
                @endif
                
                <a href="#announcement" class="group inline-flex items-center gap-3 border border-white/10 hover:border-white/20 text-zinc-300 hover:text-white font-bold text-sm pl-6 pr-3 py-3 rounded-full transition-all duration-300 hover:bg-white/5 active:scale-[0.98]">
                    <span>Sambutan Sekolah</span>
                    <!-- Circle enclosed downward icon -->
                    <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center group-hover:translate-y-0.5 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 13l-7 7-7-7" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
