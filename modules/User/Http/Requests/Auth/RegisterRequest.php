<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Base\Support\BaseRequest;
use Sadegh19b\LaravelPersianValidation\Rules\IranianMobile;

/**
 * @property-read string $mobile
 * @property-read string $password
 * @property-read string $password_confirmation
 */
final class RegisterRequest extends BaseRequest
{
    protected array $casts = [
        'mobile' => 'enNum',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => [
                'required',
                'unique:users,mobile',
                'unique:users,email',
                new IranianMobile('zero'),
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
