<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api\v1\Auth;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Exceptions\OtpSendException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Services\AuthUserService;
use Modules\User\Http\Requests\Auth\LoginWPRequest;
use Modules\User\Http\Requests\Auth\SendOtpRequest;
use Modules\User\Http\Requests\Auth\VerifyRequest;
use Modules\User\Models\User;
use Modules\User\Services\UserService;

final class LoginController
{
    public function sendOtp(SendOtpRequest $request, AuthUserService $authUserService, UserService $service): void
    {
        $mobile = $request->str('mobile')->value();
        $user = $authUserService->findByUsernameField($mobile);
        if (! $user instanceof Authenticatable) {
            $user = $service->createUser(compact('mobile'));
        }
        /** @var User $user */
        try {
            Authenticator::sendOtpToUser(OtpChannel::SMS, $user);
        } catch (OtpRateLimitException) {
            abort(429, __('OTP Retried too Soon'));
        } catch (OtpSendException) {
            abort(400, __('OTP Sending Failed'));
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

    public function wordpressLogin(LoginWPRequest $request, UserService $service): array
    {
        $tempToken = $request->validated('token');
        try {
            $response = Http::post(config('wordpress.base_url').'/wp-json/sso/v1/verify', ['token' => $tempToken])
                ->json();
            $success = $response['success'] ?? false;
            if (! $success) {
                throw new Exception($response['message'] ?? 'Error in call WordPress api');
            }
            Log::debug('WordPress verify response: '.json_encode($response));
            $userData = $response['data'];
            $userAttrs = [
                'wp_id' => $userData['id'],
                'mobile' => '0'.((int) $userData['username']),
                'name' => mb_trim(($userData['first_name'] ?? '').' '.($userData['last_name'] ?? '')),
                'email' => $userData['email'] ?? null,
            ];
            $user = $service->getByMobile($userAttrs['mobile']);
            if ($user) {
                $service->updateWPUser($user, $userAttrs);
            } else {
                $user = $service->createWPUser($userAttrs);
            }

            return Authenticator::loginUserAndIssueToken($user);
        } catch (Exception $e) {
            Log::debug('Error in wordpress verify token: '.$e->getMessage());
            abort(503, __('Error in website integration'));
        }
    }
}
