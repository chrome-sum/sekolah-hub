<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionFormField extends Model
{
    protected $table = 'admission_form_fields';

    protected $fillable = [
        'track_id',
        'field_key',
        'label',
        'type',
        'placeholder',
        'help_text',
        'is_required',
        'options',
        'validation_rules',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'options' => 'array',
    ];

    public function track(): BelongsTo
    {
        return $this->belongsTo(AdmissionTrack::class, 'track_id');
    }
}
