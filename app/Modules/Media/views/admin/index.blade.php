<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Media Manager</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola folder, gambar, dan dokumen pendukung sistem Sekolah Hub.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button onclick="document.getElementById('upload_modal').showModal()" class="btn btn-primary text-white text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-1.5 shadow-sm hover:shadow transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload File
                </button>
                <button onclick="document.getElementById('folder_modal').showModal()" class="btn btn-ghost border border-gray-200 hover:bg-gray-50 text-gray-600 text-xs font-semibold px-4 py-2.5 rounded-lg flex items-center gap-1.5 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    Folder Baru
                </button>
            </div>
        </div>

        <!-- Navigation / Breadcrumbs -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center justify-between shadow-sm">
            <div class="text-sm py-0 text-gray-600">
                <ul class="flex items-center space-x-2 text-xs font-medium">
                    <li>
                        <a href="{{ route('admin.media.index') }}" class="text-gray-400 hover:text-primary transition">Root</a>
                    </li>
                    @if($currentFolder)
                        <li class="flex items-center space-x-2">
                            <span class="text-gray-300">/</span>
                            <span class="font-bold text-gray-800">{{ $currentFolder->name }}</span>
                        </li>
                    @endif
                </ul>
            </div>
            @if($currentFolder)
                <a href="{{ route('admin.media.index', ['folder_id' => $parentFolderId]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali Ke Induk
                </a>
            @endif
        </div>

        <!-- Folders Section -->
        @if($folders->isNotEmpty())
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Folder</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    @foreach($folders as $folder)
                        <div class="group relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:border-gray-200 transition duration-200 flex flex-col items-center text-center">
                            <a href="{{ route('admin.media.index', ['folder_id' => $folder->id]) }}" class="w-full flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-400 group-hover:text-amber-500 transition duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                                <span class="mt-2 text-xs font-bold text-gray-700 truncate w-full px-1">{{ $folder->name }}</span>
                            </a>
                            
                            <!-- Delete Folder Action -->
                            <form action="{{ route('admin.media.folder.destroy', $folder->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-200" data-confirm="Apakah Anda yakin ingin menghapus folder ini beserta seluruh isinya?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-md transition shadow-sm" title="Hapus Folder">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Media Section -->
        <div class="space-y-3 pt-2">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">File Media</h3>
            @if($media->isEmpty())
                <div class="bg-white p-16 rounded-xl text-center border border-dashed border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada file media di folder ini.</p>
                    <p class="text-xs text-gray-400 mt-1">Silakan upload gambar atau dokumen pendukung.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-6">
                    @foreach($media as $item)
                        @php
                            $isImage = str_starts_with($item->mime_type, 'image/');
                            $service = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
                            $fileUrl = $service->getUrl($item->id);
                            $thumbUrl = $isImage ? $service->getUrl($item->id, 'thumbnail') : null;
                        @endphp
                        <div class="group relative bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:border-gray-200 transition duration-200 flex flex-col justify-between">
                            <!-- Preview Area -->
                            <div class="aspect-square bg-slate-50 flex items-center justify-center relative overflow-hidden">
                                @if($isImage)
                                    <img src="{{ $thumbUrl }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <!-- Document Icons -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-primary/65" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @endif
                                
                                <!-- Hover Actions Overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-200 flex items-center justify-center space-x-2">
                                    <a href="{{ $fileUrl }}" target="_blank" class="p-2 bg-white text-gray-700 hover:bg-gray-100 rounded-lg shadow-sm transition" title="View/Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus file ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-white text-rose-600 hover:bg-rose-50 rounded-lg shadow-sm transition" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- File Meta Info -->
                            <div class="p-3 bg-white border-t border-gray-50">
                                <p class="text-xs font-semibold text-gray-700 truncate" title="{{ $item->original_name }}">
                                    {{ $item->original_name }}
                                </p>
                                <div class="flex items-center justify-between mt-1 text-[10px] text-gray-400 font-mono tracking-tight">
                                    <span>{{ strtoupper($item->extension) }}</span>
                                    <span>{{ number_format($item->size / 1024, 1) }} KB</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <dialog id="upload_modal" class="modal">
        <div class="modal-box bg-white p-6 rounded-xl shadow-xl max-w-md w-full">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2.5 top-2.5">✕</button>
            </form>
            <h3 class="font-bold text-lg text-gray-900 mb-4 pb-2 border-b border-gray-100">Upload File</h3>
            
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if($currentFolder)
                    <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
                @endif
                <div class="form-control w-full">
                    <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Pilih File</label>
                    <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/25 border border-gray-200 rounded-lg p-1" required>
                </div>
                <div class="form-control w-full">
                    <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Caption (Deskripsi Singkat)</label>
                    <input type="text" name="caption" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary" placeholder="Optional description">
                </div>
                <div class="form-control w-full">
                    <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Alt Text (Aksesibilitas)</label>
                    <input type="text" name="alt_text" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary" placeholder="Alt description for screen readers">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('upload_modal').close()" class="btn btn-ghost text-gray-500 border border-gray-200 hover:bg-gray-50 text-xs font-semibold rounded-lg">Batal</button>
                    <button type="submit" class="btn btn-primary text-white text-xs font-semibold rounded-lg shadow-sm">Upload</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-gray-950/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <!-- Folder Modal -->
    <dialog id="folder_modal" class="modal">
        <div class="modal-box bg-white p-6 rounded-xl shadow-xl max-w-md w-full">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2.5 top-2.5">✕</button>
            </form>
            <h3 class="font-bold text-lg text-gray-900 mb-4 pb-2 border-b border-gray-100">Buat Folder Baru</h3>
            
            <form action="{{ route('admin.media.folder.create') }}" method="POST" class="space-y-4">
                @csrf
                @if($currentFolder)
                    <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
                @endif
                <div class="form-control w-full">
                    <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Nama Folder</label>
                    <input type="text" name="name" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary" placeholder="Folder name" required autocomplete="off">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('folder_modal').close()" class="btn btn-ghost text-gray-500 border border-gray-200 hover:bg-gray-50 text-xs font-semibold rounded-lg">Batal</button>
                    <button type="submit" class="btn btn-primary text-white text-xs font-semibold rounded-lg shadow-sm">Buat Folder</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-gray-950/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
