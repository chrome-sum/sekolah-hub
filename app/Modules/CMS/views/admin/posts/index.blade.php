<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Kelola Berita</h1>
                <p class="text-sm text-gray-500 mt-1">Tulis, edit, dan publikasikan artikel berita untuk portal sekolah.</p>
            </div>
            <div>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary text-white text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-1.5 shadow-sm hover:shadow transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Berita
                </a>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-5 rounded-xl border border-gray-100 flex flex-wrap gap-4 items-center justify-between shadow-sm">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="input input-bordered input-sm pl-9 w-64 text-sm rounded-lg focus:ring-primary focus:border-primary border-gray-200" />
                </div>
                <div>
                    <select name="category_id" class="select select-bordered select-sm text-sm rounded-lg focus:ring-primary focus:border-primary border-gray-200" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-ghost btn-sm text-gray-600 hover:bg-gray-50 rounded-lg">Saring</button>
                @if(request()->filled('search') || request()->filled('category_id'))
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost btn-sm text-gray-400 hover:text-gray-600 rounded-lg">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-4 px-6 text-left">Judul berita</th>
                            <th class="py-4 px-6 text-left">Penulis</th>
                            <th class="py-4 px-6 text-left">Kategori</th>
                            <th class="py-4 px-6 text-left">Status</th>
                            <th class="py-4 px-6 text-left">Tanggal</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/80">
                        @forelse($posts as $post)
                            <tr class="hover:bg-gray-50/30 transition duration-150">
                                <td class="py-4.5 px-6">
                                    <div class="font-semibold text-gray-900 hover:text-primary transition duration-150">{{ $post->title }}</div>
                                    <div class="text-xs text-gray-400 mt-1 font-mono tracking-tight select-all">Slug: {{ $post->slug }}</div>
                                </td>
                                <td class="py-4.5 px-6 text-gray-600 text-sm font-medium">
                                    {{ $post->author->name ?? '-' }}
                                </td>
                                <td class="py-4.5 px-6">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($post->categories as $category)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-600 border border-gray-100">{{ $category->name }}</span>
                                        @empty
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-4.5 px-6">
                                    @if($post->status === 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Diterbitkan</span>
                                    @elseif($post->status === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Draft</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-100">Diarsipkan</span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6 text-sm text-gray-500 font-mono tracking-tight">
                                    @if($post->published_at)
                                        {{ $post->published_at->format('d M Y') }}
                                        <span class="text-[10px] text-gray-400 block mt-0.5">{{ $post->published_at->format('H:i') }} WIB</span>
                                    @else
                                        <span class="italic text-gray-400 text-xs">Belum publish</span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="inline-flex items-center justify-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 bg-indigo-50/50 hover:bg-indigo-50 px-2.5 py-1.5 rounded-md transition duration-150">Edit</a>
                                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" class="inline-flex items-center justify-center text-xs font-semibold text-rose-600 hover:text-rose-900 bg-rose-50/50 hover:bg-rose-50 px-2.5 py-1.5 rounded-md transition duration-150">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-400 text-sm">
                                    Belum ada berita yang ditulis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
