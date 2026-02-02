<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api\v1\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Exceptions\OtpSendException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Services\AuthUserService;
use Modules\User\Http\Requests\Auth\ResetPassRequest;
use Modules\User\Http\Requests\Auth\SendOtpRequest;
use Modules\User\Models\User;

final class ForgetPassController
{
    public function sendOtp(SendOtpRequest $request, AuthUserService $authUserService): void
    {
        $mobile = $request->str('mobile')->value();
        $user = $authUserService->findByUsernameField($mobile, ['mobile']);
        abort_if(! $user instanceof Authenticatable, 422, __('User Not Found'));
        /** @var User $user */
        try {
            Authenticator::sendOtpToUser(OtpChannel::SMS, $user);
        } catch (OtpRateLimitException) {
            abort(429, __('OTP Retried too Soon'));
        } catch (OtpSendException) {
            abort(400, __('OTP Sending Failed'));
        }
    }

    public function resetPass(ResetPassRequest $request): User
    {
        try {
            $otp = Authenticator::verifyOtp(OtpChannel::SMS, $request->mobile, $request->code);
        } catch (InvalidOtpException) {
            abort(401, __('Invalid OTP'));
        }

        $otp->user->update(['password' => Hash::make($request->password)]);

        return $otp->user->refresh();
    }
}
