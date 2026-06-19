<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gallery.manage');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:gallery_albums,slug'],
            'description' => ['nullable', 'string'],
            'cover_media_id' => ['nullable', 'integer'],
            'status' => ['required', 'string', 'in:draft,published'],
        ];
    }
}
