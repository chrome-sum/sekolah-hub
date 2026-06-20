<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jalur Pendaftaran PPDB') }}
            </h2>
            <a href="{{ route('admin.ppdb.tracks.create') }}" class="btn btn-primary btn-sm rounded-lg">
                + Tambah Jalur
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-gray-500 text-sm border-b border-gray-200">
                                    <th>Nama Jalur</th>
                                    <th>Slug</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kuota</th>
                                    <th>Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($tracks as $track)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4">
                                            <div class="font-semibold text-gray-900">{{ $track->name }}</div>
                                            @if($track->description)
                                                <div class="text-xs text-gray-400 mt-0.5 max-w-sm truncate">{{ $track->description }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">{{ $track->slug }}</td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $track->academicYear->name }} 
                                            @if($track->academicYear->is_active)
                                                <span class="badge badge-xs bg-emerald-50 text-emerald-800 border-emerald-200">Aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">{{ $track->quota ?: 'Tidak Terbatas' }}</td>
                                        <td class="py-4">
                                            @if($track->is_active)
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-2.5 py-0.5 rounded-full">Aktif</span>
                                            @else
                                                <span class="badge badge-sm bg-gray-100 border border-gray-200 text-gray-600 px-2.5 py-0.5 rounded-full">Non-aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.ppdb.tracks.form-fields.index', $track->id) }}" class="btn btn-ghost btn-xs text-emerald-600 hover:text-emerald-900">Form Builder</a>
                                                <a href="{{ route('admin.ppdb.tracks.edit', $track->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.ppdb.tracks.destroy', $track->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus jalur pendaftaran ini? Semua field form dan pendaftaran terkait akan ikut terhapus!">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost btn-xs text-rose-600 hover:text-rose-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-400 text-sm">
                                            Belum ada data jalur pendaftaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $tracks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
