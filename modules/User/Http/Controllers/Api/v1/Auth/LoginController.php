<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api\v1\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Services\AuthUserService;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Http\Requests\Auth\SendOtpRequest;
use Modules\User\Http\Requests\Auth\VerifyRequest;
use Modules\User\Models\User;

final class LoginController
{
    public function passwordLogin(LoginRequest $request): array
    {
        $user = Authenticator::checkCredentials($request->mobile, $request->password, ['email', 'mobile']);
        abort_if(! $user instanceof User, 401, __('mobile or password is not correct'));

        return Authenticator::loginUserAndIssueToken($user);
    }

    public function sendOtp(SendOtpRequest $request, AuthUserService $authUserService): void
    {
        $mobile = $request->str('mobile')->value();
        $user = $authUserService->findByUsernameField($mobile);
        abort_if(! $user instanceof Authenticatable, 422, __('User Not Found'));
        /** @var User $user */
        try {
            Authenticator::sendOtpToUser(OtpChannel::SMS, $user);
        } catch (OtpRateLimitException) {
            abort(429, __('OTP Retried too Soon'));
        }
    }

    public function verify(VerifyRequest $request): array
    {
        try {
            $otp = Authenticator::verifyOtp(OtpChannel::SMS, $request->mobile, $request->code);

            return Authenticator::loginUserAndIssueToken($otp->user);
        } catch (InvalidOtpException) {
            abort(401, __('Invalid OTP'));
        }
    }
}
