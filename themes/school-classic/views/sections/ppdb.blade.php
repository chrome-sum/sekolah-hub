<!-- PPDB Section -->
<section id="ppdb" class="bg-zinc-950 text-white py-24 md:py-32 border-b border-zinc-900 relative overflow-hidden">
    <!-- Sophisticated background glowing orbs -->
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary/10 blur-[120px] pointer-events-none animate-pulse duration-7000"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-primary/5 blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Info & Schedule -->
            <div class="lg:col-span-4 space-y-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-zinc-300">
                        Penerimaan Siswa Baru
                    </span>
                </span>
                
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-none">
                    Portal Penerimaan PPDB Online
                </h2>
                
                <p class="text-zinc-400 text-sm leading-relaxed max-w-sm font-light">
                    Bergabunglah bersama kami untuk mematangkan potensi putra-putri Anda melalui program akademik berkelas dunia dan pendidikan karakter yang berakar kuat.
                </p>

                @if($activePPDBYear)
                    <!-- Schedule Box with Double-Bezel -->
                    <div class="bg-zinc-900/50 p-2 rounded-[2rem] border border-zinc-800/80">
                        <div class="bg-zinc-950 p-6 rounded-[calc(2rem-0.5rem)] space-y-4 shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)]">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-wider font-mono">Tahun Ajaran</span>
                                <h4 class="text-base font-bold text-white mt-0.5">{{ $activePPDBYear->name }}</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-4 border-t border-zinc-900 pt-3">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-wider font-mono">Pendaftaran</span>
                                    <p class="text-xs font-semibold text-zinc-200 mt-0.5 font-mono">
                                        {{ $activePPDBYear->registration_open_at ? $activePPDBYear->registration_open_at->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-wider font-mono">Penutupan</span>
                                    <p class="text-xs font-semibold text-zinc-200 mt-0.5 font-mono">
                                        {{ $activePPDBYear->registration_close_at ? $activePPDBYear->registration_close_at->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-zinc-900">
                                <a href="{{ route('public.ppdb.status') }}" class="text-xs font-bold text-primary hover:text-white transition duration-300 inline-flex items-center gap-1">
                                    Cek Hasil Kelulusan &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Tracks Grid -->
            <div class="lg:col-span-8">
                @if(!$activePPDBYear)
                    <!-- Empty State Double-Bezel -->
                    <div class="bg-zinc-900/40 p-2 rounded-[2rem] border border-dashed border-zinc-800">
                        <div class="bg-zinc-950 p-12 rounded-[calc(2rem-0.5rem)] text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-zinc-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <h3 class="font-bold text-white text-lg mb-1">Pendaftaran Belum Dibuka</h3>
                            <p class="text-zinc-500 text-sm font-light">PPDB untuk tahun ajaran baru saat ini belum aktif. Silakan hubungi bagian informasi sekolah.</p>
                        </div>
                    </div>
                @else
                    @if($activePPDBTracks->isEmpty())
                        <div class="bg-zinc-900/40 p-2 rounded-[2rem] border border-dashed border-zinc-800">
                            <div class="bg-zinc-950 p-12 rounded-[calc(2rem-0.5rem)] text-center">
                                <p class="text-zinc-500 text-sm font-light">Jalur pendaftaran sedang dipersiapkan. Hubungi admin sekolah untuk info lebih lanjut.</p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($activePPDBTracks as $track)
                                <!-- Track Card with Double Bezel & Asymmetric Button -->
                                <div class="group bg-zinc-900/60 p-2 rounded-[2rem] border border-zinc-800/80 hover:border-zinc-700 hover:bg-zinc-900/90 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                                    <div class="bg-zinc-900 rounded-[calc(2rem-0.5rem)] p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)] flex flex-col justify-between h-52">
                                        <div>
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-[9px] uppercase font-bold text-primary tracking-widest px-2.5 py-0.5 bg-transparent rounded-full border border-primary">
                                                    Jalur {{ $track->code }}
                                                </span>
                                            </div>
                                            <h3 class="font-bold text-white text-lg mb-2 group-hover:text-primary transition-colors duration-300">
                                                {{ $track->name }}
                                            </h3>
                                            <p class="text-zinc-400 text-xs font-light line-clamp-3 leading-relaxed">
                                                {{ $track->description ?: 'Pendaftaran masuk siswa baru melalui seleksi ' . strtolower($track->name) . '.' }}
                                            </p>
                                        </div>
                                        <div>
                                            <a href="{{ route('public.ppdb.register', $track->slug) }}" class="group/btn inline-flex items-center justify-between bg-primary hover:bg-primary/95 text-white font-bold text-xs pl-5 pr-1.5 py-1.5 rounded-full transition-all duration-300 w-full active:scale-[0.98]">
                                                <span>Daftar Sekarang</span>
                                                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center group-hover/btn:translate-x-0.5 transition duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</section>
