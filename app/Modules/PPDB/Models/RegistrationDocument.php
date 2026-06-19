<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDocument extends Model
{
    protected $table = 'registration_documents';

    protected $fillable = [
        'registration_id',
        'field_id',
        'original_name',
        'stored_name',
        'mime_type',
        'extension',
        'size',
        'path',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'size' => 'integer',
        'verified_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(AdmissionFormField::class, 'field_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
