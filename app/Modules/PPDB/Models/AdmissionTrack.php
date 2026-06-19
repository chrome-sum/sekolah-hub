<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionTrack extends Model
{
    protected $table = 'admission_tracks';

    protected $fillable = [
        'academic_year_id',
        'name',
        'slug',
        'quota',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'quota' => 'integer',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(AdmissionFormField::class, 'track_id')->orderBy('sort_order', 'asc');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'track_id');
    }
}
