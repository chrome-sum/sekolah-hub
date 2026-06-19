<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tahun Ajaran PPDB') }}
            </h2>
            <a href="{{ route('admin.ppdb.academic-years.create') }}" class="btn btn-primary btn-sm rounded-lg">
                + Tambah Tahun Ajaran
            </a>
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
            @if(session('error'))
                <div class="alert alert-error mb-6 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-rose-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-gray-500 text-sm border-b border-gray-200">
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Status</th>
                                    <th>Pendaftaran Dibuka</th>
                                    <th>Pendaftaran Ditutup</th>
                                    <th>Pengumuman</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($academicYears as $year)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4 font-semibold text-gray-900">{{ $year->name }}</td>
                                        <td class="py-4 text-gray-600">{{ $year->code }}</td>
                                        <td class="py-4">
                                            @if($year->is_active)
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-2.5 py-0.5 rounded-full">Aktif</span>
                                            @else
                                                <span class="badge badge-sm bg-gray-100 border border-gray-200 text-gray-600 px-2.5 py-0.5 rounded-full">Non-aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $year->registration_open_at ? $year->registration_open_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $year->registration_close_at ? $year->registration_close_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $year->announcement_at ? $year->announcement_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.ppdb.academic-years.edit', $year->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.ppdb.academic-years.destroy', $year->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus tahun ajaran ini?')" class="btn btn-ghost btn-xs text-rose-600 hover:text-rose-900" {{ $year->is_active ? 'disabled' : '' }}>Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-8 text-gray-400 text-sm">
                                            Belum ada data tahun ajaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $academicYears->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
