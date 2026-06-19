<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    protected $table = 'registrations';

    protected $fillable = [
        'registration_number',
        'academic_year_id',
        'track_id',
        'status',
        'submitted_at',
        'verified_at',
        'accepted_at',
        'rejected_at',
        'announcement_published_at',
        'locked_at',
        'notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'announcement_published_at' => 'datetime',
        'locked_at' => 'datetime',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(AdmissionTrack::class, 'track_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(RegistrationValue::class, 'registration_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class, 'registration_id');
    }

    /**
     * Helper to get a EAV value by field key.
     */
    public function getValue(string $fieldKey)
    {
        $valueModel = $this->values()
            ->whereHas('field', function ($q) use ($fieldKey) {
                $q->where('field_key', $fieldKey);
            })->first();

        if (!$valueModel) {
            return null;
        }

        return $valueModel->real_value;
    }
}
