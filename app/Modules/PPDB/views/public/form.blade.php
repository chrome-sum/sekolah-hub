<x-public-layout>
    @section('title', 'Formulir Pendaftaran ' . $track->name . ' - ' . config('app.name'))

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <!-- Heading -->
                <div class="border-b border-gray-100 pb-5 mb-6">
                    <span class="text-xs text-primary font-bold uppercase tracking-widest block mb-1">Formulir Online</span>
                    <h1 class="text-2xl font-extrabold text-gray-900">Jalur Pendaftaran: {{ $track->name }}</h1>
                    <p class="text-sm text-gray-500 mt-2">
                        Tahun Ajaran {{ $academicYear->name }}. Harap isi semua kolom bertanda <span class="text-rose-500 font-bold">*</span> dengan data yang benar.
                    </p>
                </div>

                <!-- Validation Alerts -->
                @if ($errors->any())
                    <div class="alert alert-error bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl mb-6">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <h3 class="font-bold text-sm">Gagal mengirim formulir.</h3>
                                <ul class="list-disc list-inside mt-1 text-xs space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Registration Form -->
                <form action="{{ route('public.ppdb.submit', $track->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="track_id" value="{{ $track->id }}">

                    <div class="space-y-5">
                        @foreach($fields as $field)
                            @if($field->type === 'heading')
                                <div class="border-b border-gray-100 pt-6 pb-2">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $field->label }}</h3>
                                    @if($field->help_text)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $field->help_text }}</p>
                                    @endif
                                </div>
                            @elseif($field->type === 'description')
                                <div class="bg-blue-50/30 border border-blue-100 rounded-xl p-4 text-sm text-gray-600 leading-relaxed">
                                    @if($field->label)
                                        <h4 class="font-bold text-gray-800 mb-1">{{ $field->label }}</h4>
                                    @endif
                                    {{ $field->help_text ?: $field->placeholder }}
                                </div>
                            @else
                                <div class="form-control w-full">
                                    <label class="label font-semibold text-gray-700 text-sm py-1">
                                        <span>
                                            {{ $field->label }}
                                            @if($field->is_required)
                                                <span class="text-rose-500 font-bold">*</span>
                                            @endif
                                        </span>
                                    </label>

                                    <!-- Render Input Element dynamically -->
                                    @if($field->type === 'textarea')
                                        <textarea 
                                            name="fields[{{ $field->field_key }}]" 
                                            placeholder="{{ $field->placeholder }}" 
                                            class="textarea textarea-bordered w-full h-24 focus:ring-primary focus:border-primary @error('fields.'.$field->field_key) textarea-error @enderror"
                                            {{ $field->is_required ? 'required' : '' }}>{{ old('fields.'.$field->field_key) }}</textarea>

                                    @elseif($field->type === 'select')
                                        <select 
                                            name="fields[{{ $field->field_key }}]" 
                                            class="select select-bordered w-full focus:ring-primary focus:border-primary @error('fields.'.$field->field_key) select-error @enderror"
                                            {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">{{ $field->placeholder ?: 'Pilih salah satu...' }}</option>
                                            @if(is_array($field->options))
                                                @foreach($field->options as $opt)
                                                    <option value="{{ $opt }}" {{ old('fields.'.$field->field_key) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    @elseif($field->type === 'radio')
                                        <div class="flex flex-col sm:flex-row gap-4 mt-1 bg-gray-50 border border-gray-100 rounded-xl p-3">
                                            @if(is_array($field->options))
                                                @foreach($field->options as $opt)
                                                    <label class="label cursor-pointer justify-start gap-2 py-0">
                                                        <input 
                                                            type="radio" 
                                                            name="fields[{{ $field->field_key }}]" 
                                                            value="{{ $opt }}" 
                                                            class="radio radio-primary"
                                                            {{ old('fields.'.$field->field_key) === $opt ? 'checked' : '' }}
                                                            {{ $field->is_required ? 'required' : '' }} />
                                                        <span class="label-text text-gray-700 text-sm font-medium">{{ $opt }}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>

                                    @elseif($field->type === 'checkbox')
                                        <div class="flex flex-col gap-3 mt-1 bg-gray-50 border border-gray-100 rounded-xl p-3">
                                            @if(is_array($field->options))
                                                @foreach($field->options as $opt)
                                                    @php
                                                        $oldValues = old('fields.'.$field->field_key) ?: [];
                                                    @endphp
                                                    <label class="label cursor-pointer justify-start gap-2 py-0">
                                                        <input 
                                                            type="checkbox" 
                                                            name="fields[{{ $field->field_key }}][]" 
                                                            value="{{ $opt }}" 
                                                            class="checkbox checkbox-primary checkbox-sm"
                                                            {{ in_array($opt, $oldValues) ? 'checked' : '' }} />
                                                        <span class="label-text text-gray-700 text-sm font-medium">{{ $opt }}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>

                                    @elseif($field->type === 'file')
                                        <input 
                                            type="file" 
                                            name="fields[{{ $field->field_key }}]" 
                                            class="file-input file-input-bordered w-full focus:ring-primary @error('fields.'.$field->field_key) file-input-error @enderror"
                                            {{ $field->is_required ? 'required' : '' }} />

                                    @else
                                        <!-- text, number, date, email, phone -->
                                        <input 
                                            type="{{ $field->type === 'phone' ? 'tel' : $field->type }}" 
                                            name="fields[{{ $field->field_key }}]" 
                                            value="{{ old('fields.'.$field->field_key) }}"
                                            placeholder="{{ $field->placeholder }}" 
                                            class="input input-bordered w-full focus:ring-primary focus:border-primary @error('fields.'.$field->field_key) input-error @enderror"
                                            {{ $field->is_required ? 'required' : '' }} />
                                    @endif

                                    @if($field->help_text)
                                        <p class="text-xs text-gray-400 mt-1">{{ $field->help_text }}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Turnstile Captcha Container -->
                    @if($turnstileSiteKey)
                        <div class="form-control w-full mt-6 flex justify-center items-center">
                            <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                            @error('cf-turnstile-response')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-3 justify-end">
                        <a href="{{ route('public.ppdb.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary text-white font-medium px-6">Kirim Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($turnstileSiteKey)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
</x-public-layout>
