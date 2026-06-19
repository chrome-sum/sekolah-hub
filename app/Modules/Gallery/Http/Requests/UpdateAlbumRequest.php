<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlbumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gallery.manage');
    }

    public function rules(): array
    {
        $album = $this->route('gallery');
        $albumId = is_object($album) ? $album->id : $album;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:gallery_albums,slug,' . ($albumId ?? '')],
            'description' => ['nullable', 'string'],
            'cover_media_id' => ['nullable', 'integer'],
            'status' => ['required', 'string', 'in:draft,published'],
            'items' => ['nullable', 'array'],
            'items.*.media_id' => ['required', 'integer'],
            'items.*.caption' => ['nullable', 'string', 'max:255'],
            'items.*.sort_order' => ['required', 'integer'],
        ];
    }
}
