<!-- PPDB Section -->
<section id="ppdb" class="bg-zinc-950 text-white py-20 border-b border-zinc-900 relative overflow-hidden">
    <!-- Subtle gradient accent -->
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary/10 blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Info & Schedule -->
            <div class="lg:col-span-4 space-y-6">
                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-primary">
                    ADMISI / PENDAFTARAN
                </span>
                
                <h2 class="text-3xl font-bold tracking-tight text-white leading-tight">
                    Penerimaan Peserta Didik Baru
                </h2>
                
                <p class="text-zinc-400 text-sm leading-relaxed max-w-sm">
                    Bergabunglah bersama kami untuk mematangkan potensi putra-putri Anda melalui program akademik berkelas dunia dan pendidikan karakter yang berakar kuat.
                </p>

                @if($activePPDBYear)
                    <!-- Schedule Box -->
                    <div class="bg-zinc-900/60 border border-zinc-800 p-6 rounded-2xl space-y-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-wider">Tahun Ajaran</span>
                            <h4 class="text-base font-bold text-white">{{ $activePPDBYear->name }}</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-wider">Pendaftaran</span>
                                <p class="text-sm font-semibold text-zinc-200">
                                    {{ $activePPDBYear->registration_open_at ? $activePPDBYear->registration_open_at->format('d M Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-wider">Penutupan</span>
                                <p class="text-sm font-semibold text-zinc-200">
                                    {{ $activePPDBYear->registration_close_at ? $activePPDBYear->registration_close_at->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-zinc-800/80">
                            <a href="{{ route('public.ppdb.status') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                Cek Status Kelulusan &rarr;
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Tracks Grid -->
            <div class="lg:col-span-8">
                @if(!$activePPDBYear)
                    <div class="bg-zinc-900/40 border border-dashed border-zinc-800 p-12 rounded-2xl text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-zinc-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="font-bold text-white text-lg mb-1">Pendaftaran Belum Dibuka</h3>
                        <p class="text-zinc-500 text-sm">PPDB untuk tahun ajaran baru saat ini belum aktif. Silakan hubungi bagian informasi sekolah.</p>
                    </div>
                @else
                    @if($activePPDBTracks->isEmpty())
                        <div class="bg-zinc-900/40 border border-dashed border-zinc-800 p-12 rounded-2xl text-center">
                            <p class="text-zinc-500 text-sm">Jalur pendaftaran sedang dipersiapkan. Hubungi admin sekolah untuk info lebih lanjut.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($activePPDBTracks as $track)
                                <div class="bg-zinc-900 border border-zinc-800/80 p-6 rounded-2xl hover:border-zinc-700 transition duration-300 flex flex-col justify-between h-52">
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs uppercase font-extrabold text-primary tracking-widest px-2.5 py-0.5 bg-primary/10 rounded-full">
                                                Jalur {{ $track->code }}
                                            </span>
                                        </div>
                                        <h3 class="font-bold text-white text-lg mb-2">
                                            Jalur {{ $track->name }}
                                        </h3>
                                        <p class="text-zinc-400 text-xs line-clamp-2">
                                            {{ $track->description ?: 'Pendaftaran masuk siswa baru melalui seleksi ' . strtolower($track->name) . '.' }}
                                        </p>
                                    </div>
                                    <div class="pt-4">
                                        <a href="{{ route('public.ppdb.register', $track->slug) }}" class="btn btn-primary btn-sm rounded-lg w-full text-xs font-bold active:scale-[0.98]">
                                            Daftar Sekarang
                                        </a>
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
