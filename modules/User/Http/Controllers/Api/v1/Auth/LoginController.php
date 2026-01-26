<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api\v1\Auth;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Services\AuthUserService;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Http\Requests\Auth\SendOtpRequest;
use Modules\User\Http\Requests\Auth\VerifyRequest;
use Modules\User\Models\User;
use Modules\User\Services\UserService;

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

    public function wordpressLogin(Request $request, UserService $service): array
    {
        $tempToken = $request->get('token');
        try {
            $response = Http::post(env('WP_URL').'/wp-json/sso/v1/verify', ['token' => $tempToken])
                ->json();
            $success = $response['success'] ?? false;
            if (! $success) {
                throw new Exception($response['message'] ?? 'Error in call WordPress api');
            }
            Log::debug('WordPress verify response: '.json_encode($response));
            $userData = $response['data'];
            $user = $service->getByEmail($userData['email']);
            if ($user) {
                $service->updateWPUser($user, Arr::only($userData, ['name', 'first_name', 'last_name', 'display_name']));
            } else {
                $user = $service->createWPUser($userData);
            }

            return Authenticator::loginUserAndIssueToken($user);
        } catch (Exception $e) {
            Log::debug('Error in wordpress verify token: '.$e->getMessage());
            abort(503, __('Error in website integration'));
        }
    }
}
