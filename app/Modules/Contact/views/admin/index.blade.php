<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pesan Masuk') }}
            </h2>
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
                <form action="{{ route('admin.contacts.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                    <div class="form-control">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, subjek, isi..." class="input input-bordered input-sm w-64 text-sm focus:ring-primary focus:border-primary" />
                    </div>
                    <div class="form-control">
                        <select name="status" class="select select-bordered select-sm text-sm focus:ring-primary focus:border-primary" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-link btn-sm text-gray-500">Reset</a>
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
                                    <th>Pengirim</th>
                                    <th>Telepon</th>
                                    <th>Subjek</th>
                                    <th>Status</th>
                                    <th>Tanggal Masuk</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($messages as $message)
                                    <tr class="hover:bg-gray-50/50 transition {{ $message->status === 'unread' ? 'font-semibold bg-blue-50/10' : '' }}">
                                        <td class="py-4">
                                            <div class="text-gray-900">{{ $message->name }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $message->email }}</div>
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $message->phone ?: '-' }}
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm max-w-xs truncate">
                                            {{ $message->subject }}
                                        </td>
                                        <td class="py-4">
                                            @if($message->status === 'unread')
                                                <span class="badge badge-sm bg-amber-50 border border-amber-200 text-amber-800 px-2.5 py-0.5 rounded-full">Belum Dibaca</span>
                                            @elseif($message->status === 'read')
                                                <span class="badge badge-sm bg-blue-50 border border-blue-200 text-blue-800 px-2.5 py-0.5 rounded-full">Sudah Dibaca</span>
                                            @elseif($message->status === 'replied')
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-2.5 py-0.5 rounded-full">Sudah Dibalas</span>
                                            @else
                                                <span class="badge badge-sm bg-gray-100 border border-gray-200 text-gray-700 px-2.5 py-0.5 rounded-full">Diarsipkan</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-sm text-gray-500">
                                            {{ $message->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Lihat</a>
                                                <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus pesan ini?')" class="btn btn-ghost btn-xs text-rose-600 hover:text-rose-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-400 text-sm">
                                            Tidak ada pesan masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
