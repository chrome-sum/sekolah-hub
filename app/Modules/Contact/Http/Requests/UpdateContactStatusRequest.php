<?php

declare(strict_types=1);

namespace App\Modules\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('contact.manage');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:unread,read,replied,archived'],
        ];
    }
}
