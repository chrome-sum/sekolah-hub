<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryAlbum extends Model
{
    use SoftDeletes;

    protected $table = 'gallery_albums';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_media_id',
        'status',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(GalleryAlbumItem::class, 'album_id')->orderBy('sort_order', 'asc');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Resolve cover image URL using MediaServiceInterface.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_media_id) {
            return null;
        }

        try {
            $mediaService = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
            return $mediaService->getUrl((int) $this->cover_media_id);
        } catch (\Exception $e) {
            return null;
        }
    }
}
