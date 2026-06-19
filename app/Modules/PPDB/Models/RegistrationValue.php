<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationValue extends Model
{
    protected $table = 'registration_values';

    protected $fillable = [
        'registration_id',
        'field_id',
        'value_text',
        'value_number',
        'value_date',
        'value_boolean',
    ];

    protected $casts = [
        'value_number' => 'float',
        'value_date' => 'date',
        'value_boolean' => 'boolean',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(AdmissionFormField::class, 'field_id');
    }

    /**
     * Get the real typed value from EAV.
     */
    public function getRealValueAttribute()
    {
        $type = $this->field->type ?? 'text';

        switch ($type) {
            case 'number':
                return $this->value_number;
            case 'date':
                return $this->value_date;
            case 'checkbox':
                if (empty($this->value_text)) {
                    return [];
                }
                try {
                    return json_decode($this->value_text, true) ?: [];
                } catch (\Exception $e) {
                    return [];
                }
            case 'boolean':
                return $this->value_boolean;
            default:
                return $this->value_text;
        }
    }
}
