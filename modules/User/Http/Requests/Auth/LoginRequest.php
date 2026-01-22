<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Base\Support\BaseRequest;
use Sadegh19b\LaravelPersianValidation\Rules\IranianMobile;

/**
 * @property-read string $mobile
 * @property-read string $password
 */
final class LoginRequest extends BaseRequest
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
                new IranianMobile('zero'),
            ],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
