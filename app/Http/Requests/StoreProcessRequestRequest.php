<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Base\Rules\ExistAll;

final class StoreProcessRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string', 'max:2048'],
            'images' => ['required', 'array', 'min:1', 'max:10', new ExistAll('media', 'uuid', 'uuid')],
        ];
    }
}
