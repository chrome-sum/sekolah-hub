<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Galeri Foto') }}
            </h2>
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Album
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filter Panel -->
            <div class="bg-white p-4 mb-6 rounded-lg shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.gallery.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                    <div class="form-control">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari album..." class="input input-bordered input-sm w-64 text-sm focus:ring-primary focus:border-primary" />
                    </div>
                    <button type="submit" class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-link btn-sm text-gray-500">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-gray-500 text-sm border-b border-gray-200">
                                    <th class="w-24">Sampul</th>
                                    <th>Judul Album</th>
                                    <th>Jumlah Foto</th>
                                    <th>Status</th>
                                    <th>Tanggal Diterbitkan</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($albums as $album)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4">
                                            @if($album->cover_image_url)
                                                <img src="{{ $album->cover_image_url }}" class="w-16 h-10 object-cover rounded border border-gray-200 shadow-sm" />
                                            @else
                                                <div class="w-16 h-10 bg-gray-100 flex items-center justify-center rounded border border-gray-200 text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <div class="font-semibold text-gray-900">{{ $album->title }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Slug: {{ $album->slug }}</div>
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm font-medium">
                                            {{ $album->items_count ?? $album->items()->count() }} Foto
                                        </td>
                                        <td class="py-4">
                                            @if($album->status === 'published')
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-0.5 font-medium rounded-full">Diterbitkan</span>
                                            @else
                                                <span class="badge badge-sm bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-0.5 font-medium rounded-full">Draft</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-sm text-gray-500">
                                            @if($album->published_at)
                                                {{ $album->published_at->format('d M Y H:i') }}
                                            @else
                                                <span class="italic text-gray-400">Belum publish</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.gallery.edit', $album->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.gallery.destroy', $album->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus album galeri ini beserta pengaitan fotonya?">
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
                                            Belum ada album galeri yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $albums->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
