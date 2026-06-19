<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pendaftar PPDB') }}
            </h2>
            @if(request()->filled('track_id'))
                <a href="{{ route('admin.ppdb.registrations.export', ['track_id' => request('track_id')]) }}" class="btn btn-emerald btn-sm text-white rounded-lg flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Excel
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filter Panel -->
            <div class="bg-white p-4 mb-6 rounded-lg shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.ppdb.registrations.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                    <div class="form-control">
                        <select name="track_id" class="select select-bordered select-sm text-sm focus:ring-primary focus:border-primary" onchange="this.form.submit()">
                            <option value="">Semua Jalur</option>
                            @foreach($tracks as $track)
                                <option value="{{ $track->id }}" {{ request('track_id') == $track->id ? 'selected' : '' }}>
                                    {{ $track->name }} ({{ $track->academicYear->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <select name="status" class="select select-bordered select-sm text-sm focus:ring-primary focus:border-primary" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Dikirim (Submitted)</option>
                            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Sedang Ditinjau</option>
                            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Mengundurkan Diri</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->filled('track_id') || request()->filled('status'))
                        <a href="{{ route('admin.ppdb.registrations.index') }}" class="btn btn-link btn-sm text-gray-500">Reset</a>
                    @endif
                </form>

                @if(!request()->filled('track_id'))
                    <span class="text-xs text-gray-400">Pilih salah satu <b>Jalur</b> untuk mengaktifkan tombol ekspor Excel.</span>
                @endif
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-gray-500 text-sm border-b border-gray-200">
                                    <th>No. Pendaftaran</th>
                                    <th>Jalur / TA</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($registrations as $reg)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4 font-mono font-semibold text-gray-900">
                                            {{ $reg->registration_number }}
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            <span class="font-medium text-gray-800">{{ $reg->track->name }}</span>
                                            <span class="text-gray-400 block text-xs">Tahun Ajaran: {{ $reg->academicYear->code }}</span>
                                        </td>
                                        <td class="py-4">
                                            @if($reg->status === 'draft')
                                                <span class="badge badge-sm bg-gray-100 border border-gray-200 text-gray-700 px-2.5 py-0.5 rounded-full">Draft</span>
                                            @elseif($reg->status === 'submitted')
                                                <span class="badge badge-sm bg-blue-50 border border-blue-200 text-blue-800 px-2.5 py-0.5 rounded-full">Submitted</span>
                                            @elseif($reg->status === 'under_review')
                                                <span class="badge badge-sm bg-amber-50 border border-amber-200 text-amber-800 px-2.5 py-0.5 rounded-full">Ditinjau</span>
                                            @elseif($reg->status === 'verified')
                                                <span class="badge badge-sm bg-indigo-50 border border-indigo-200 text-indigo-800 px-2.5 py-0.5 rounded-full">Terverifikasi</span>
                                            @elseif($reg->status === 'accepted')
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-2.5 py-0.5 rounded-full">Diterima</span>
                                            @elseif($reg->status === 'rejected')
                                                <span class="badge badge-sm bg-rose-50 border border-rose-200 text-rose-800 px-2.5 py-0.5 rounded-full">Ditolak</span>
                                            @else
                                                <span class="badge badge-sm bg-gray-200 border border-gray-300 text-gray-600 px-2.5 py-0.5 rounded-full">Batal</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-sm text-gray-500">
                                            {{ $reg->submitted_at ? $reg->submitted_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('admin.ppdb.registrations.show', $reg->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Detail & Verifikasi</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-400 text-sm">
                                            Belum ada pendaftar masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
