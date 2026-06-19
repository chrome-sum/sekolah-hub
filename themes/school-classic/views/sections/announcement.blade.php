@php
    $headmasterImageUrl = null;
    if (!empty($themeSettings['announcement_image_media_id'])) {
        try {
            $headmasterImageUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl((int) $themeSettings['announcement_image_media_id']);
        } catch (\Exception $e) {}
    }
@endphp

<!-- Announcement Section -->
<section id="announcement" class="bg-white py-20 border-b border-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Image Frame -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-full max-w-[340px]">
                    <div class="absolute -inset-4 bg-primary/5 rounded-2xl transform -rotate-3"></div>
                    <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-md border border-gray-100 bg-gray-50">
                        @if($headmasterImageUrl)
                            <img src="{{ $headmasterImageUrl }}" alt="{{ $themeSettings['announcement_author'] ?? 'Kepala Sekolah' }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop" alt="Kepala Sekolah" class="w-full h-full object-cover">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-primary">
                    PENGANTAR SEKOLAH
                </span>
                
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">
                    {{ $themeSettings['announcement_title'] ?? 'Sambutan Kepala Sekolah' }}
                </h2>
                
                <div class="text-gray-600 leading-relaxed text-base border-l-4 border-primary pl-6 py-2 italic bg-gray-50/50 rounded-r-lg pr-4">
                    "{{ $themeSettings['announcement_content'] ?? 'Selamat datang di website resmi Sekolah Hub. Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi demi membina potensi unggul setiap peserta didik.' }}"
                </div>
                
                <div class="pt-2">
                    <h4 class="font-bold text-gray-900 text-lg">
                        {{ $themeSettings['announcement_author'] ?? 'Drs. Hasbi Al-Aziz, M.Pd.' }}
                    </h4>
                    <p class="text-sm text-gray-500 font-medium">
                        {{ $themeSettings['announcement_author_role'] ?? 'Kepala Sekolah Hub' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
