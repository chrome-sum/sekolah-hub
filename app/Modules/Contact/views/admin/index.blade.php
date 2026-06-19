<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Pesan Masuk</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar pesan dan saran yang dikirimkan pengunjung melalui formulir kontak.</p>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-5 rounded-xl border border-gray-100 flex flex-wrap gap-4 items-center justify-between shadow-sm">
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, subjek, isi..." class="input input-bordered input-sm pl-9 w-64 text-sm rounded-lg focus:ring-primary focus:border-primary border-gray-200" />
                </div>
                <div>
                    <select name="status" class="select select-bordered select-sm text-sm rounded-lg focus:ring-primary focus:border-primary border-gray-200" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-ghost btn-sm text-gray-600 hover:bg-gray-50 rounded-lg">Saring</button>
                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-ghost btn-sm text-gray-400 hover:text-gray-600 rounded-lg">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table View -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="table w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-4 px-6 text-left">Pengirim</th>
                            <th class="py-4 px-6 text-left">Telepon</th>
                            <th class="py-4 px-6 text-left">Subjek</th>
                            <th class="py-4 px-6 text-left">Status</th>
                            <th class="py-4 px-6 text-left">Tanggal Masuk</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/80">
                        @forelse($messages as $message)
                            <tr class="hover:bg-gray-50/30 transition duration-150 {{ $message->status === 'unread' ? 'bg-slate-50/40 font-semibold' : '' }}">
                                <td class="py-4.5 px-6">
                                    <div class="text-gray-900">{{ $message->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5 font-mono select-all">{{ $message->email }}</div>
                                </td>
                                <td class="py-4.5 px-6 text-gray-600 text-sm font-mono tracking-tight">
                                    {{ $message->phone ?: '-' }}
                                </td>
                                <td class="py-4.5 px-6 text-gray-600 text-sm max-w-xs truncate">
                                    {{ $message->subject }}
                                </td>
                                <td class="py-4.5 px-6">
                                    @if($message->status === 'unread')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Belum Dibaca</span>
                                    @elseif($message->status === 'read')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">Sudah Dibaca</span>
                                    @elseif($message->status === 'replied')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Sudah Dibalas</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-100">Diarsipkan</span>
                                    @endif
                                </td>
                                <td class="py-4.5 px-6 text-sm text-gray-500 font-mono tracking-tight">
                                    {{ $message->created_at->format('d M Y') }}
                                    <span class="text-[10px] text-gray-400 block mt-0.5">{{ $message->created_at->format('H:i') }} WIB</span>
                                </td>
                                <td class="py-4.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <a href="{{ route('admin.contacts.show', $message->id) }}" class="inline-flex items-center justify-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 bg-indigo-50/50 hover:bg-indigo-50 px-2.5 py-1.5 rounded-md transition duration-150">Lihat</a>
                                        <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')" class="inline-flex items-center justify-center text-xs font-semibold text-rose-600 hover:text-rose-900 bg-rose-50/50 hover:bg-rose-50 px-2.5 py-1.5 rounded-md transition duration-150">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-400 text-sm">
                                    Tidak ada pesan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
