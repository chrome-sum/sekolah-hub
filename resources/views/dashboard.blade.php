<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="border-b border-gray-100 pb-5">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas dan metrik penting sistem portal sekolah.</p>
        </div>

        <!-- Welcome Card -->
        <div class="relative overflow-hidden bg-gradient-to-r from-sidebar-bg to-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm text-white">
            <!-- Background Decoration -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 text-xs font-medium text-primary-light">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sesi Aktif
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-slate-300 text-sm font-light max-w-xl">
                        Anda masuk sebagai <span class="font-semibold text-white uppercase text-xs px-2 py-0.5 rounded bg-white/10">{{ Auth::user()->roles->pluck('name')->first() ?? 'Admin' }}</span>. Kelola dan pantau seluruh operasional sistem melalui menu pintasan di bawah ini.
                    </p>
                </div>
                
                <!-- Quick Date Display -->
                <div class="text-left sm:text-right shrink-0">
                    <div class="text-xs text-slate-400 font-medium uppercase tracking-wider">Tanggal Hari Ini</div>
                    <div class="text-lg font-bold mt-0.5 font-mono tracking-tight">{{ date('d M Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card: News Count -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition duration-300 hover:shadow-md">
                <div class="p-3.5 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11h3m-3 4h2" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Berita Terbit</div>
                    <div class="text-2xl font-extrabold text-gray-900 mt-1 font-mono tracking-tight">{{ $newsCount }}</div>
                </div>
            </div>

            <!-- Card: PPDB Registrations -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition duration-300 hover:shadow-md">
                <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Pendaftaran Baru</div>
                    <div class="text-2xl font-extrabold text-gray-900 mt-1 font-mono tracking-tight">{{ $newRegistrations }}</div>
                </div>
            </div>

            <!-- Card: Unread Messages -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition duration-300 hover:shadow-md">
                <div class="p-3.5 bg-amber-50 text-amber-600 rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5M6 18h12M6 21h12" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Pesan Unread</div>
                    <div class="text-2xl font-extrabold text-gray-900 mt-1 font-mono tracking-tight">{{ $unreadContacts }}</div>
                </div>
            </div>

            <!-- Card: System Users -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 transition duration-300 hover:shadow-md">
                <div class="p-3.5 bg-purple-50 text-purple-600 rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Pengguna</div>
                    <div class="text-2xl font-extrabold text-gray-900 mt-1 font-mono tracking-tight">{{ $usersCount }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-gray-900">Akses Cepat Pengelolaan</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Action: Write News -->
                <a href="{{ route('admin.posts.create') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50/20 transition active:scale-[0.98]">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Tulis Berita</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:translate-x-1 group-hover:text-blue-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Action: PPDB -->
                <a href="{{ route('admin.ppdb.registrations.index') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-emerald-100 hover:bg-emerald-50/20 transition active:scale-[0.98]">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Kelola Pendaftar</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:translate-x-1 group-hover:text-emerald-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Action: Read Contacts -->
                <a href="{{ route('admin.contacts.index') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-amber-100 hover:bg-amber-50/20 transition active:scale-[0.98]">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5m4.5 0L12 14.25" />
                            </svg>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Lihat Pesan</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:translate-x-1 group-hover:text-amber-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Action: Settings -->
                <a href="{{ route('admin.settings.index') }}" class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-purple-100 hover:bg-purple-50/20 transition active:scale-[0.98]">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Pengaturan</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:translate-x-1 group-hover:text-purple-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
