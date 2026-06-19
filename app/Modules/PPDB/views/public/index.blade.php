<x-public-layout>
    @section('title', 'Penerimaan Peserta Didik Baru (PPDB) - ' . config('app.name'))

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Hero Heading Section -->
            <div class="text-center space-y-4 py-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Penerimaan Peserta Didik Baru (PPDB)
                </h1>
                @if($activeYear)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Selamat datang di portal pendaftaran online siswa baru {{ config('app.name') }} tahun ajaran <span class="font-semibold text-primary">{{ $activeYear->name }}</span>.
                    </p>
                @else
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Portal pendaftaran online siswa baru {{ config('app.name') }}.
                    </p>
                @endif

                <div class="flex justify-center gap-3 pt-2">
                    <a href="{{ route('public.ppdb.status') }}" class="btn btn-outline btn-primary btn-sm rounded-lg">
                        Cek Status Kelulusan
                    </a>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-error bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-rose-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Main Content Container -->
            @if($activeYear)
                <!-- Schedule Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Jadwal Pelaksanaan PPDB
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border-l-4 border-primary pl-4 py-1">
                            <span class="text-xs text-gray-400 font-semibold block uppercase tracking-wider">Pendaftaran Dibuka</span>
                            <span class="text-gray-800 font-bold block mt-1">
                                {{ $activeYear->registration_open_at ? $activeYear->registration_open_at->format('d M Y') : '-' }}
                            </span>
                            <span class="text-xs text-gray-500 block mt-0.5">
                                Pukul {{ $activeYear->registration_open_at ? $activeYear->registration_open_at->format('H:i') . ' WIB' : '-' }}
                            </span>
                        </div>
                        <div class="border-l-4 border-rose-500 pl-4 py-1">
                            <span class="text-xs text-gray-400 font-semibold block uppercase tracking-wider">Pendaftaran Ditutup</span>
                            <span class="text-gray-800 font-bold block mt-1">
                                {{ $activeYear->registration_close_at ? $activeYear->registration_close_at->format('d M Y') : '-' }}
                            </span>
                            <span class="text-xs text-gray-500 block mt-0.5">
                                Pukul {{ $activeYear->registration_close_at ? $activeYear->registration_close_at->format('H:i') . ' WIB' : '-' }}
                            </span>
                        </div>
                        <div class="border-l-4 border-emerald-500 pl-4 py-1">
                            <span class="text-xs text-gray-400 font-semibold block uppercase tracking-wider">Pengumuman Hasil</span>
                            <span class="text-gray-800 font-bold block mt-1">
                                {{ $activeYear->announcement_at ? $activeYear->announcement_at->format('d M Y') : '-' }}
                            </span>
                            <span class="text-xs text-gray-500 block mt-0.5">
                                Pukul {{ $activeYear->announcement_at ? $activeYear->announcement_at->format('H:i') . ' WIB' : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Admission Tracks -->
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Pilih Jalur Pendaftaran
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($tracks as $track)
                            <div class="card bg-white border border-gray-100 hover:border-primary/30 shadow-sm hover:shadow-md transition duration-300 rounded-2xl p-6 flex flex-col justify-between h-full">
                                <div>
                                    <div class="flex justify-between items-start gap-2">
                                        <h3 class="font-bold text-lg text-gray-900">{{ $track->name }}</h3>
                                        <span class="badge badge-sm bg-blue-50 border border-blue-200 text-blue-800">
                                            Kuota: {{ $track->quota ?: '∞' }}
                                        </span>
                                    </div>
                                    @if($track->description)
                                        <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                                            {{ $track->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-50">
                                    @php
                                        $now = now();
                                        $isOpen = true;
                                        if ($activeYear->registration_open_at && $now->lt($activeYear->registration_open_at)) {
                                            $isOpen = false;
                                        }
                                        if ($activeYear->registration_close_at && $now->gt($activeYear->registration_close_at)) {
                                            $isOpen = false;
                                        }
                                    @endphp

                                    @if($isOpen)
                                        <a href="{{ route('public.ppdb.register', $track->slug) }}" class="btn btn-primary w-full text-white font-medium rounded-xl btn-sm">
                                            Daftar Sekarang
                                        </a>
                                    @else
                                        <button disabled class="btn btn-disabled w-full font-medium rounded-xl btn-sm">
                                            Pendaftaran Ditutup
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-400">
                                Belum ada jalur pendaftaran yang dibuka untuk tahun ajaran aktif ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <!-- No Active PPDB Banner -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center max-w-lg mx-auto space-y-4">
                    <div class="mx-auto w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Pendaftaran Belum Dibuka</h2>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Saat ini tidak ada masa Penerimaan Peserta Didik Baru (PPDB) yang sedang aktif atau dibuka. Silakan hubungi pihak sekolah untuk informasi lebih lanjut.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
