<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Kelola Halaman</h1>
                <p class="text-sm text-gray-500 mt-1">Buat dan kelola halaman statis untuk website sekolah Anda.</p>
            </div>
            <div>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary text-white text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-1.5 shadow-sm hover:shadow transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Halaman
                </a>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-5 rounded-xl border border-gray-100 flex flex-wrap gap-4 items-center justify-between shadow-sm">
            <form action="{{ route('admin.pages.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari halaman..." class="input input-bordered input-sm pl-9 w-64 text-sm rounded-lg focus:ring-primary focus:border-primary border-gray-200" />
                </div>
                <button type="submit" class="btn btn-ghost btn-sm text-gray-600 hover:bg-gray-50 rounded-lg">Saring</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-ghost btn-sm text-gray-400 hover:text-gray-600 rounded-lg">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-4 px-6 text-left">Judul halaman</th>
                            <th class="py-4 px-6 text-left">Halaman induk (Parent)</th>
                            <th class="py-4 px-6 text-left">Status</th>
                            <th class="py-4 px-6 text-left">Terakhir Diubah</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/80">
                        @forelse($pages as $page)
                            <tr class="hover:bg-gray-50/30 transition duration-150">
                                <td class="py-4.5 px-6">
                                    <div class="font-semibold text-gray-900 hover:text-primary transition duration-150">{{ $page->title }}</div>
                                    <div class="text-xs text-gray-400 mt-1 font-mono tracking-tight select-all">Slug: {{ $page->slug }}</div>
                                </td>
                                <td class="py-4.5 px-6 text-gray-600 text-sm font-medium">
                                    {{ $page->parent->title ?? '-' }}
                                </td>
                                <td class="py-4.5 px-6">
                                    @if($page->status === 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Diterbitkan</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Draft</span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6 text-sm text-gray-500 font-mono tracking-tight">
                                    {{ $page->updated_at->format('d M Y') }}
                                    <span class="text-[10px] text-gray-400 block mt-0.5">{{ $page->updated_at->format('H:i') }} WIB</span>
                                </td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="inline-flex items-center justify-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 bg-indigo-50/50 hover:bg-indigo-50 px-2.5 py-1.5 rounded-md transition duration-150">Edit</a>
                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus halaman ini? (Halaman anak akan kehilangan referensi induknya)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center text-xs font-semibold text-rose-600 hover:text-rose-900 bg-rose-50/50 hover:bg-rose-50 px-2.5 py-1.5 rounded-md transition duration-150">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-400 text-sm">
                                    Belum ada halaman yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pages->hasPages())
                <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
