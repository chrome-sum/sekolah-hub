<x-public-layout>
    @section('title', 'Hubungi Kami - ' . config('app.name', 'Sekolah Hub'))

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumbs -->
        <div class="text-sm breadcrumbs mb-6 text-gray-500">
            <ul>
                <li><a href="/">Beranda</a></li>
                <li>Hubungi Kami</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 items-start">
            <!-- Form Card -->
            <div class="md:col-span-3 card bg-white shadow-sm border border-gray-100 p-6 sm:p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kirim Pesan</h1>
                <p class="text-xs text-gray-500 mb-6">Hubungi kami melalui form di bawah ini. Kami akan merespons pesan Anda secepatnya.</p>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-start shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @error('email')
                    @if(str_contains($message, 'Terlalu banyak'))
                        <div class="alert alert-error mb-6 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg flex items-start shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-rose-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @endif
                @enderror

                <form action="{{ route('public.contact.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-gray-700">Nama Lengkap</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ketik nama Anda di sini..." class="input input-bordered w-full @error('name') input-error @enderror" required />
                        @error('name')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold text-gray-700">Email</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="input input-bordered w-full @error('email') input-error @enderror" required />
                            @error('email')
                                @if(!str_contains($message, 'Terlalu banyak'))
                                    <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text font-semibold text-gray-700">Nomor Telepon/HP (Opsional)</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" class="input input-bordered w-full @error('phone') input-error @enderror" />
                            @error('phone')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-gray-700">Subjek</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Apa perihal pesan Anda?" class="input input-bordered w-full @error('subject') input-error @enderror" required />
                        @error('subject')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-gray-700">Pesan</span></label>
                        <textarea name="message" rows="5" placeholder="Tulis pesan lengkap Anda di sini..." class="textarea textarea-bordered w-full text-sm @error('message') textarea-error @enderror" required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cloudflare Turnstile Verification -->
                    @if(!empty($turnstileSiteKey))
                        <div class="form-control w-full my-4 flex justify-start">
                            <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                            @error('cf-turnstile-response')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-full mt-4">Kirim Pesan</button>
                </form>
            </div>

            <!-- School Information Cards -->
            <div class="md:col-span-2 space-y-6">
                <!-- Contact info card -->
                <div class="card bg-white shadow-sm border border-gray-100 p-6 text-sm">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <div>
                                <span class="font-semibold text-gray-800">Alamat Sekolah</span>
                                <p class="text-gray-500 mt-1 leading-relaxed">Jl. Pendidikan No. 45, Jakarta Selatan</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <div>
                                <span class="font-semibold text-gray-800">Telepon</span>
                                <p class="text-gray-500 mt-1 leading-relaxed">(021) 555-1234</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <div>
                                <span class="font-semibold text-gray-800">Email</span>
                                <p class="text-gray-500 mt-1 leading-relaxed">info@sekolah.sch.id</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Turnstile JS Script Loader -->
    @if(!empty($turnstileSiteKey))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
</x-public-layout>
