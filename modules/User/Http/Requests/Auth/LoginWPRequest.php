<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Base\Support\BaseRequest;

/**
 * @property-read string $mobile
 * @property-read string $password
 */
final class LoginWPRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'max:255'],
        ];
    }
}
