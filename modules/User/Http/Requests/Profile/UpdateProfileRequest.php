<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Profile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Modules\Base\Support\BaseRequest;
use Modules\User\Enums\Gender;

final class UpdateProfileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
        ];
    }
}
