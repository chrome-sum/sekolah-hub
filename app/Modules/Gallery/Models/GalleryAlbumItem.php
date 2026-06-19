<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryAlbumItem extends Model
{
    protected $table = 'gallery_album_items';

    // No standard updated_at/created_at pair, only created_at is present
    public $timestamps = false;

    protected $fillable = [
        'album_id',
        'media_id',
        'caption',
        'sort_order',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($item) {
            $item->created_at = $item->freshTimestamp();
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    /**
     * Resolve media URL using MediaServiceInterface.
     */
    public function getUrlAttribute(): ?string
    {
        try {
            $mediaService = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
            return $mediaService->getUrl((int) $this->media_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Resolve medium size URL using MediaServiceInterface.
     */
    public function getMediumUrlAttribute(): ?string
    {
        try {
            $mediaService = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
            return $mediaService->getUrl((int) $this->media_id, 'medium');
        } catch (\Exception $e) {
            return $this->url; // Fallback to original
        }
    }

    /**
     * Resolve thumbnail URL using MediaServiceInterface.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        try {
            $mediaService = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
            return $mediaService->getUrl((int) $this->media_id, 'thumbnail');
        } catch (\Exception $e) {
            return $this->url; // Fallback to original
        }
    }
}
