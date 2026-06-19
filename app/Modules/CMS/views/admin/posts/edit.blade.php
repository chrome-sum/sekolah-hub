<x-app-layout>
    <div class="space-y-6" x-data="{
        featuredMediaId: '{{ old('featured_media_id', $post->featured_media_id) }}',
        featuredMediaUrl: '{{ old('featured_media_id', $post->featured_media_id) ? $post->featured_image_url : '' }}',
        selectMedia(id, url) {
            this.featuredMediaId = id;
            this.featuredMediaUrl = url;
            document.getElementById('media_modal').close();
        },
        clearMedia() {
            this.featuredMediaId = '';
            this.featuredMediaUrl = '';
        }
    }">
        <!-- Header Section -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Berita</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui artikel berita dan pengaturannya.</p>
            </div>
            <div>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost btn-sm text-gray-500 hover:text-gray-700 rounded-lg flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            @method('PUT')

            <!-- Main Content Editor -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-5">
                    <div class="form-control w-full">
                        <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Judul Berita</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" placeholder="Ketik judul berita di sini..." class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary @error('title') input-error @enderror" required />
                        @error('title')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="url-berita-otomatis-jika-kosong" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary" />
                        @error('slug')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Kutipan Singkat (Excerpt)</label>
                        <textarea name="excerpt" rows="3" placeholder="Ringkasan berita singkat..." class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Konten Berita</label>
                        <div class="prose max-w-none border border-gray-200 rounded-lg p-1 bg-gray-50/10">
                            <x-rich-text::trix id="post-content" name="content" :value="old('content', $post->content?->toTrixHtml())" />
                        </div>
                        @error('content')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-5">
                    <div>
                        <h3 class="font-bold text-base text-gray-900">Pengaturan SEO</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Optimalkan tampilan berita Anda di mesin pencari seperti Google.</p>
                    </div>
                    <div class="border-t border-gray-50 pt-4 space-y-4">
                        <div class="form-control w-full">
                            <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">SEO Title</label>
                            <input type="text" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" placeholder="Judul khusus untuk hasil pencarian Google" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">SEO Description</label>
                            <textarea name="seo_description" rows="3" placeholder="Deskripsi khusus untuk Google snippet" class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary">{{ old('seo_description', $post->seo_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="space-y-6">
                <!-- Publish Card -->
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-5">
                    <h3 class="font-bold text-base text-gray-900 border-b border-gray-50 pb-3">Publikasi</h3>

                    <div class="form-control w-full">
                        <label class="label-text text-gray-700 font-semibold mb-1.5 text-[13px]">Status</label>
                        <select name="status" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Diterbitkan</option>
                            <option value="archived" {{ old('status', $post->status) === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-primary flex-1 text-white text-xs font-semibold rounded-lg shadow-sm">Perbarui</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost text-gray-500 border border-gray-200 hover:bg-gray-50 text-xs font-semibold rounded-lg">Batal</a>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-5">
                    <h3 class="font-bold text-base text-gray-900 border-b border-gray-50 pb-3">Kategori Berita</h3>
                    
                    <div class="form-control max-h-60 overflow-y-auto space-y-2.5 pr-2 custom-scrollbar">
                        @forelse($categories as $category)
                            @php
                                $isChecked = is_array(old('categories')) 
                                    ? in_array((string)$category->id, old('categories')) 
                                    : $post->categories->contains($category->id);
                            @endphp
                            <label class="flex items-center gap-3 cursor-pointer py-0.5">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                    class="checkbox checkbox-primary checkbox-sm rounded"
                                    {{ $isChecked ? 'checked' : '' }} />
                                <span class="text-sm text-gray-700 select-none">{{ $category->name }}</span>
                            </label>
                        @empty
                            <div class="text-xs text-gray-400 italic py-2">
                                Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="text-primary hover:underline">Buat Baru</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Featured Image Card -->
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-5">
                    <h3 class="font-bold text-base text-gray-900 border-b border-gray-50 pb-3">Gambar Unggulan</h3>
                    
                    <input type="hidden" name="featured_media_id" :value="featuredMediaId" />
                    
                    <!-- Media Preview -->
                    <div>
                        <template x-if="featuredMediaUrl">
                            <div class="relative group aspect-video bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                                <img :src="featuredMediaUrl" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button type="button" @click="clearMedia()" class="btn btn-circle btn-sm btn-error text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="!featuredMediaUrl">
                            <div class="border-2 border-dashed border-gray-200 rounded-lg aspect-video flex flex-col items-center justify-center text-gray-400 p-4 bg-gray-50/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-xs text-gray-400">Belum ada gambar terpilih</span>
                            </div>
                        </template>
                    </div>

                    <button type="button" onclick="document.getElementById('media_modal').showModal()" class="btn btn-sm btn-outline btn-primary w-full text-xs font-semibold rounded-lg">
                        Pilih Gambar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Media Picker Modal -->
    <dialog id="media_modal" class="modal">
        <div class="modal-box max-w-4xl bg-white rounded-xl shadow-xl p-6">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2.5 top-2.5">✕</button>
            </form>
            <h3 class="font-bold text-lg text-gray-900 mb-4 pb-2 border-b border-gray-100">Pilih Gambar Unggulan</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 max-h-[400px] overflow-y-auto p-1 custom-scrollbar">
                @forelse($mediaList as $media)
                    @php
                        $imageUrl = '';
                        try {
                            $imageUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl($media->id);
                        } catch (\Exception $e) {
                            $imageUrl = '';
                        }
                    @endphp
                    @if($imageUrl)
                        <div class="border border-gray-200 rounded-lg overflow-hidden cursor-pointer hover:border-primary transition group relative aspect-square"
                             @click="selectMedia('{{ $media->id }}', '{{ $imageUrl }}')">
                            <img src="{{ $imageUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200" />
                            <div class="absolute bottom-0 inset-x-0 bg-black/60 p-1.5 text-[10px] text-white truncate text-center font-mono">
                                {{ $media->filename }}
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400 text-sm">
                        Tidak ada media yang ditemukan. Silakan upload media terlebih dahulu di menu Media.
                    </div>
                @endforelse
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-gray-950/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
