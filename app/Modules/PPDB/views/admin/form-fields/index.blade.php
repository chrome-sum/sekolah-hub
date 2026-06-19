<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Form Builder: ') }} {{ $track->name }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kelola formulir pendaftaran dinamis untuk jalur ini.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.ppdb.tracks.index') }}" class="btn btn-ghost btn-sm">
                    Kembali
                </a>
                <a href="{{ route('admin.ppdb.tracks.form-fields.create', $track->id) }}" class="btn btn-primary btn-sm rounded-lg">
                    + Tambah Field
                </a>
            </div>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Daftar Field Formulir</h3>
                    <span class="text-xs text-gray-400">Gunakan tombol arah untuk menyusun urutan field di form.</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr class="text-gray-500 text-sm border-b border-gray-200">
                                <th class="w-12">Urutan</th>
                                <th>Label / Nama Field</th>
                                <th>Key</th>
                                <th>Tipe</th>
                                <th>Required?</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($fields as $index => $field)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4 font-mono text-xs text-gray-400">
                                        {{ $field->sort_order }}
                                    </td>
                                    <td class="py-4">
                                        <div class="font-semibold text-gray-900">{{ $field->label }}</div>
                                        @if($field->help_text)
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $field->help_text }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 font-mono text-xs text-gray-600">
                                        {{ $field->field_key }}
                                    </td>
                                    <td class="py-4 text-gray-600 text-sm">
                                        <span class="badge badge-sm badge-ghost">{{ strtoupper($field->type) }}</span>
                                    </td>
                                    <td class="py-4 text-sm">
                                        @if($field->is_required)
                                            <span class="text-rose-600 font-medium">Ya</span>
                                        @else
                                            <span class="text-gray-400">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        @if($field->is_active)
                                            <span class="badge badge-xs bg-emerald-50 text-emerald-800 border-emerald-200">Aktif</span>
                                        @else
                                            <span class="badge badge-xs bg-gray-100 text-gray-600 border-gray-200">Draf</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Up/Down sorting forms -->
                                            <form action="{{ route('admin.ppdb.tracks.form-fields.reorder', $track->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="order[]" value="{{ $field->id }}">
                                                @foreach($fields as $otherField)
                                                    @if($otherField->id !== $field->id)
                                                        <input type="hidden" name="order[]" value="{{ $otherField->id }}">
                                                    @endif
                                                @endforeach
                                                <!-- We can just reorder in controller, but let's make it simpler via inline buttons -->
                                            </form>

                                            <!-- Simple order update using post request -->
                                            @if($index > 0)
                                                <form action="{{ route('admin.ppdb.tracks.form-fields.reorder', $track->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @foreach($fields as $k => $f)
                                                        @if($k === $index - 1)
                                                            <input type="hidden" name="order[]" value="{{ $field->id }}">
                                                        @elseif($k === $index)
                                                            <input type="hidden" name="order[]" value="{{ $fields[$index - 1]->id }}">
                                                        @else
                                                            <input type="hidden" name="order[]" value="{{ $f->id }}">
                                                        @endif
                                                    @endforeach
                                                    <button type="submit" title="Naikkan" class="btn btn-ghost btn-xs text-gray-500 hover:text-primary">▲</button>
                                                </form>
                                            @else
                                                <span class="text-gray-200 px-1 text-xs">▲</span>
                                            @endif

                                            @if($index < count($fields) - 1)
                                                <form action="{{ route('admin.ppdb.tracks.form-fields.reorder', $track->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @foreach($fields as $k => $f)
                                                        @if($k === $index)
                                                            <input type="hidden" name="order[]" value="{{ $fields[$index + 1]->id }}">
                                                        @elseif($k === $index + 1)
                                                            <input type="hidden" name="order[]" value="{{ $field->id }}">
                                                        @else
                                                            <input type="hidden" name="order[]" value="{{ $f->id }}">
                                                        @endif
                                                    @endforeach
                                                    <button type="submit" title="Turunkan" class="btn btn-ghost btn-xs text-gray-500 hover:text-primary">▼</button>
                                                </form>
                                            @else
                                                <span class="text-gray-200 px-1 text-xs">▼</span>
                                            @endif

                                            <a href="{{ route('admin.ppdb.tracks.form-fields.edit', [$track->id, $field->id]) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                            
                                            <form action="{{ route('admin.ppdb.tracks.form-fields.destroy', [$track->id, $field->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus field form ini? Data nilai pendaftar yang sudah tersimpan untuk field ini akan hilang!')" class="btn btn-ghost btn-xs text-rose-600 hover:text-rose-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-gray-400 text-sm">
                                        Belum ada field formulir. Silakan tambah field baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
