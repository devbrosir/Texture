<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api\v1\Auth;

use App\Enums\ActivityType;
use App\Facades\ActivityLogger;
use Illuminate\Support\Arr;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Exceptions\OtpSendException;
use Modules\Auth\Facades\Authenticator;
use Modules\User\Http\Requests\Auth\RegisterRequest;
use Modules\User\Http\Requests\Auth\VerifyRegisterRequest;
use Modules\User\Services\RegistrationService;

final class RegisterController
{
    public function register(RegisterRequest $request): void
    {
        // send otp
        try {
            Authenticator::sendOtp(OtpChannel::SMS, $request->mobile, [
                'password' => $request->password,
            ]);
        } catch (OtpRateLimitException) {
            abort(429, __('OTP Retried too Soon'));
        } catch (OtpSendException) {
            abort(400, __('OTP Sending Failed'));
        }
    }

    public function verify(VerifyRegisterRequest $request, RegistrationService $registrationService): array
    {
        try {
            $otp = Authenticator::verifyOtp(OtpChannel::SMS, $request->mobile, $request->code);
        } catch (InvalidOtpException) {
            abort(401, __('Invalid OTP'));
        }

        $user = $registrationService->verifyRegistration($request->validated(), $otp);
        $otp->update(['extra' => Arr::except($otp->extra, 'password')]);
        ActivityLogger::log(ActivityType::REGISTER, $user, $user->id);

        return Authenticator::loginUserAndIssueToken($user->refresh());
    }
}
