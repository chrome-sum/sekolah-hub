@php
    try {
        $systemService = app(\App\Modules\System\Contracts\SystemServiceInterface::class);
        $primaryColor = $systemService->getSetting('theme.primary_color', '#3B82F6');
    } catch (\Exception $e) {
        $primaryColor = '#3B82F6';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sekolah Hub') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-rich-text::styles theme="daisyui" />

    <style>
        :root {
            --primary: {{ $primaryColor }};
        }
    </style>
</head>
<body class="font-sans antialiased text-body-dark bg-app-bg transition-colors duration-200">
    <div x-data="{ 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', 
        mobileOpen: false,
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        }
    }" class="flex min-h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-admin.sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- Topbar -->
            <x-admin.topbar />

            <!-- Content Area -->
            <main class="flex-1 p-6 overflow-y-auto bg-app-bg">
                <!-- Breadcrumb -->
                <x-admin.breadcrumb />

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-[13px] font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-center justify-between p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-800 shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-[13px] font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>

        <!-- Mobile Drawer Overlay -->
        <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 z-20 bg-gray-900/50 md:hidden" x-transition.opacity></div>
    </div>

    <!-- Global Confirmation Modal -->
    <div 
        x-data="{ 
            open: false, 
            title: 'Konfirmasi Hapus', 
            message: 'Apakah Anda yakin?', 
            confirmCallback: null,
            init() {
                window.addEventListener('show-confirm', (e) => {
                    this.message = e.detail.message;
                    this.title = e.detail.title || 'Konfirmasi Hapus';
                    this.confirmCallback = e.detail.onConfirm;
                    this.open = true;
                });
            },
            actionConfirm() {
                this.open = false;
                if (this.confirmCallback) this.confirmCallback();
            }
        }"
        x-show="open" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <!-- Modal Box -->
        <div 
            @click.away="open = false"
            class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="scale-95 opacity-0 translate-y-4"
            x-transition:enter-end="scale-100 opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="scale-100 opacity-100 translate-y-0"
            x-transition:leave-end="scale-95 opacity-0 translate-y-4"
        >
            <!-- Icon & Title -->
            <div class="flex items-start gap-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-full shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-base font-bold text-gray-900" x-text="title"></h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed" x-text="message"></p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-6">
                <button @click="open = false" class="btn btn-ghost btn-sm rounded-lg font-semibold text-gray-500 hover:text-gray-700">
                    Batal
                </button>
                <button @click="actionConfirm()" class="btn btn-error btn-sm rounded-lg font-semibold text-white px-5 bg-rose-600 border-rose-600 hover:bg-rose-700 hover:border-rose-700 transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('submit', function (e) {
                const form = e.target;
                const confirmMsg = form.getAttribute('data-confirm') || (e.submitter && e.submitter.getAttribute('data-confirm'));
                if (confirmMsg && !form.dataset.confirmed) {
                    e.preventDefault();
                    window.dispatchEvent(new CustomEvent('show-confirm', {
                        detail: {
                            message: confirmMsg,
                            title: form.getAttribute('data-confirm-title') || 'Konfirmasi Hapus',
                            onConfirm: () => {
                                form.dataset.confirmed = 'true';
                                if (e.submitter) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = e.submitter.name;
                                    input.value = e.submitter.value;
                                    form.appendChild(input);
                                }
                                form.submit();
                            }
                        }
                    }));
                }
            });
        });
    </script>
</body>
</html>