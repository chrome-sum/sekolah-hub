<x-public-layout>
    @section('title', 'Cek Status Pendaftaran PPDB - ' . config('app.name'))

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Search Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 text-center space-y-5">
                <div>
                    <span class="text-xs text-primary font-bold uppercase tracking-widest block mb-1">Pengumuman</span>
                    <h1 class="text-2xl font-extrabold text-gray-900">Cek Status Pendaftaran PPDB</h1>
                    <p class="text-xs text-gray-500 mt-2">
                        Masukkan Nomor Pendaftaran Anda (format: `PPDB-YYYY-XXXXXX`) untuk melacak status verifikasi berkas dan pengumuman hasil kelulusan.
                    </p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-left flex items-start gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('public.ppdb.status') }}" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-lg mx-auto">
                    <input 
                        type="text" 
                        name="registration_number" 
                        value="{{ request('registration_number') }}" 
                        placeholder="Contoh: PPDB-2026-000001" 
                        class="input input-bordered flex-grow focus:ring-primary focus:border-primary text-center font-mono font-semibold"
                        required />
                    <button type="submit" class="btn btn-primary text-white font-medium px-6">Cari Data</button>
                </form>
            </div>

            <!-- Search Results -->
            @if($searched)
                @if($registration)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-gray-100 pb-5">
                            <div>
                                <span class="text-xs text-gray-400 font-semibold block uppercase tracking-wider">Nomor Pendaftaran</span>
                                <span class="text-lg font-mono font-bold text-gray-900">{{ $registration->registration_number }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 font-semibold block uppercase tracking-wider mb-1 sm:text-right">Status Pendaftaran</span>
                                <div class="sm:text-right">
                                    @if($registration->status === 'draft')
                                        <span class="badge bg-gray-100 border border-gray-200 text-gray-700 font-medium rounded-full px-3 py-1 text-sm">Draft</span>
                                    @elseif($registration->status === 'submitted')
                                        <span class="badge bg-blue-50 border border-blue-200 text-blue-800 font-medium rounded-full px-3 py-1 text-sm">Dikirim (Submitted)</span>
                                    @elseif($registration->status === 'under_review')
                                        <span class="badge bg-amber-50 border border-amber-200 text-amber-800 font-medium rounded-full px-3 py-1 text-sm">Sedang Ditinjau</span>
                                    @elseif($registration->status === 'verified')
                                        <span class="badge bg-indigo-50 border border-indigo-200 text-indigo-800 font-medium rounded-full px-3 py-1 text-sm">Terverifikasi</span>
                                    @elseif($registration->status === 'accepted')
                                        <span class="badge bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium rounded-full px-3 py-1 text-sm">Diterima (Lulus)</span>
                                    @elseif($registration->status === 'rejected')
                                        <span class="badge bg-rose-50 border border-rose-200 text-rose-800 font-medium rounded-full px-3 py-1 text-sm">Ditolak (Tidak Lulus)</span>
                                    @else
                                        <span class="badge bg-gray-200 border border-gray-300 text-gray-600 font-medium rounded-full px-3 py-1 text-sm">Batal</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Notes / Feedback from Admin -->
                        @if($registration->notes)
                            <div class="p-4 rounded-xl border {{ $registration->status === 'accepted' ? 'bg-emerald-50 border-emerald-200 text-emerald-950' : ($registration->status === 'rejected' ? 'bg-rose-50 border-rose-200 text-rose-950' : 'bg-gray-50 border-gray-200 text-gray-800') }} text-sm space-y-1">
                                <h4 class="font-bold">Pesan dari Panitia PPDB:</h4>
                                <p>{!! nl2br(e($registration->notes)) !!}</p>
                            </div>
                        @endif

                        <!-- Summary of Biodata -->
                        <div class="space-y-4">
                            <h3 class="font-bold text-gray-900 text-sm border-b border-gray-50 pb-2">Ringkasan Pendaftaran</h3>
                            <div class="space-y-3">
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <span class="text-gray-400">Jalur Pendaftaran</span>
                                    <span class="col-span-2 text-gray-800 font-medium">{{ $registration->track->name }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <span class="text-gray-400">Tahun Ajaran</span>
                                    <span class="col-span-2 text-gray-800 font-medium">{{ $registration->academicYear->name }}</span>
                                </div>

                                <!-- Display first few EAV values as summary -->
                                @foreach($registration->values->take(4) as $val)
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <span class="text-gray-400">{{ $val->field->label }}</span>
                                        <span class="col-span-2 text-gray-800 font-medium">
                                            @if(is_array($val->real_value))
                                                {{ implode(', ', $val->real_value) }}
                                            @elseif(is_bool($val->real_value))
                                                {{ $val->real_value ? 'Ya' : 'Tidak' }}
                                            @else
                                                {{ $val->real_value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Documents status checklist -->
                        @if($registration->documents->isNotEmpty())
                            <div class="space-y-4 border-t border-gray-100 pt-5">
                                <h3 class="font-bold text-gray-900 text-sm pb-1">Verifikasi Berkas Dokumen</h3>
                                <div class="space-y-2">
                                    @foreach($registration->documents as $doc)
                                        <div class="flex items-center justify-between text-sm p-3 rounded-xl border border-gray-50 bg-gray-50/30">
                                            <span class="text-gray-600 font-medium">{{ $doc->field->label }}</span>
                                            <div>
                                                @if($doc->verification_status === 'pending')
                                                    <span class="badge badge-sm bg-amber-50 border border-amber-200 text-amber-800 px-2 py-0.5 rounded-full">Pending / Ditinjau</span>
                                                @elseif($doc->verification_status === 'approved')
                                                    <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-2 py-0.5 rounded-full">Sah / Disetujui</span>
                                                @else
                                                    <span class="badge badge-sm bg-rose-50 border border-rose-200 text-rose-800 px-2 py-0.5 rounded-full" title="{{ $doc->verification_notes }}">Ditolak</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Not Found -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center space-y-3">
                        <div class="mx-auto w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Pendaftaran Tidak Ditemukan</h2>
                        <p class="text-xs text-gray-500 leading-relaxed max-w-sm mx-auto">
                            Nomor pendaftaran yang Anda masukkan tidak terdaftar di sistem kami. Mohon periksa kembali kesesuaian penulisan nomor pendaftaran Anda.
                        </p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-public-layout>
