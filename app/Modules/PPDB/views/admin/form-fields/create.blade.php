<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Field Formulir: ') }} {{ $track->name }}
            </h2>
            <a href="{{ route('admin.ppdb.tracks.form-fields.index', $track->id) }}" class="btn btn-ghost btn-sm">
                Batal
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ type: '{{ old('type', 'text') }}' }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <form action="{{ route('admin.ppdb.tracks.form-fields.store', $track->id) }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700 text-sm">Label Field <span class="text-rose-500">*</span></label>
                            <input type="text" name="label" value="{{ old('label') }}" placeholder="Contoh: Nama Lengkap, Nomor KIP, Upload Ijazah" class="input input-bordered w-full @error('label') input-error @enderror" required />
                            @error('label')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700 text-sm">Key Field (Otomatis dibuat jika kosong)</label>
                            <input type="text" name="field_key" value="{{ old('field_key') }}" placeholder="Contoh: nama_lengkap, nomor_kip (gunakan huruf kecil dan underscore)" class="input input-bordered w-full @error('field_key') input-error @enderror" />
                            <p class="text-xs text-gray-400 mt-1">Key unik untuk identifikasi data di database. Contoh: `nama_lengkap`.</p>
                            @error('field_key')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700 text-sm">Tipe Field <span class="text-rose-500">*</span></label>
                            <select name="type" x-model="type" class="select select-bordered w-full @error('type') select-error @enderror" required>
                                @foreach($types as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Options field (only visible for select, radio, checkbox) -->
                        <div class="form-control w-full" x-show="type === 'select' || type === 'radio' || type === 'checkbox'">
                            <label class="label font-medium text-gray-700 text-sm">Pilihan (Options) <span class="text-rose-500">*</span></label>
                            <textarea name="options" placeholder="Masukkan setiap pilihan di baris baru&#10;Contoh:&#10;Laki-laki&#10;Perempuan" class="textarea textarea-bordered w-full h-32 @error('options') textarea-error @enderror">{{ old('options') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Masukkan satu pilihan per baris.</p>
                            @error('options')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700 text-sm">Placeholder (Teks Petunjuk)</label>
                            <input type="text" name="placeholder" value="{{ old('placeholder') }}" placeholder="Contoh: Masukkan nama lengkap Anda..." class="input input-bordered w-full @error('placeholder') input-error @enderror" />
                            @error('placeholder')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700 text-sm">Help Text (Penjelasan Tambahan)</label>
                            <input type="text" name="help_text" value="{{ old('help_text') }}" placeholder="Contoh: Sesuaikan dengan ijazah / akta lahir" class="input input-bordered w-full @error('help_text') input-error @enderror" />
                            @error('help_text')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700 text-sm">Custom Validation Rules (Opsional)</label>
                            <input type="text" name="validation_rules" value="{{ old('validation_rules') }}" placeholder="Contoh: numeric|digits:10 atau file|mimes:pdf,jpg|max:2048" class="input input-bordered w-full @error('validation_rules') input-error @enderror" />
                            <p class="text-xs text-gray-400 mt-1">Gunakan format aturan validasi Laravel. Pisahkan dengan tanda pipe (|). Jika kosong, sistem akan menggunakan validasi bawaan tipe data.</p>
                            @error('validation_rules')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="hidden" name="is_required" value="0">
                                <input type="checkbox" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }} class="checkbox checkbox-primary" />
                                <span class="label-text font-medium text-gray-700 text-sm">Field ini Wajib Diisi (Required)</span>
                            </label>
                        </div>

                        <div class="form-control mt-2">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="checkbox checkbox-primary" />
                                <span class="label-text font-medium text-gray-700 text-sm">Aktifkan Field ini</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.ppdb.tracks.form-fields.index', $track->id) }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Field</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
