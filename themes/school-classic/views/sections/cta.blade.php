<!-- CTA Section -->
<section id="cta" class="bg-primary text-primary-content py-16 relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="max-w-3xl mx-auto space-y-6">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight leading-snug">
                {{ $themeSettings['cta_title'] ?? 'Penerimaan Peserta Didik Baru (PPDB) Telah Dibuka!' }}
            </h2>
            <div class="flex justify-center pt-2">
                <a href="{{ $themeSettings['cta_button_url'] ?? '/ppdb' }}" class="btn btn-neutral btn-md rounded-lg shadow-md hover:-translate-y-[0.5px] transition active:scale-[0.98] font-bold text-white px-8">
                    {{ $themeSettings['cta_button_text'] ?? 'Daftar PPDB Online' }}
                </a>
            </div>
        </div>
    </div>
</section>
