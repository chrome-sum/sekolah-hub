<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('ppdb.manage');
    }

    public function rules(): array
    {
        $id = $this->route('academic_year')?->id ?? $this->route('academic_year');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:academic_years,code,' . ($id ?? '')],
            'is_active' => ['nullable', 'boolean'],
            'registration_open_at' => ['required', 'date'],
            'registration_close_at' => ['required', 'date', 'after:registration_open_at'],
            'announcement_at' => ['required', 'date', 'after:registration_close_at'],
        ];
    }
}
