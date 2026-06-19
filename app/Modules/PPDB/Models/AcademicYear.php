<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'registration_open_at',
        'registration_close_at',
        'announcement_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registration_open_at' => 'datetime',
        'registration_close_at' => 'datetime',
        'announcement_at' => 'datetime',
    ];

    public function tracks(): HasMany
    {
        return $this->hasMany(AdmissionTrack::class, 'academic_year_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
