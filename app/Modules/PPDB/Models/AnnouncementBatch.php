<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementBatch extends Model
{
    protected $table = 'announcement_batches';

    protected $fillable = [
        'academic_year_id',
        'track_id',
        'name',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(AdmissionTrack::class, 'track_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
