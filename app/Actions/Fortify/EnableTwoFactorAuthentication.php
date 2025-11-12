<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Collection;
use Laravel\Fortify\Contracts\EnableTwoFactorAuthenticationResponse;
use Laravel\Fortify\RecoveryCode;
use PragmaRX\Google2FA\Google2FA;

class EnableTwoFactorAuthentication
{
    public function __invoke($user)
    {
        $google2fa = app(Google2FA::class);

        $secretKey = $google2fa->generateSecretKey();

        // ★ SafeTimeCard 固定
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'SafeTimeCard',   // ← アプリ名（issuer）
            $user->email,     // ← 表示されるアカウント
            $secretKey
        );

        $user->forceFill([
            'two_factor_secret' => encrypt($secretKey),
            'two_factor_recovery_codes' => encrypt(json_encode(
                Collection::times(8, fn () => RecoveryCode::generate())->all()
            )),
        ])->save();

        return app(EnableTwoFactorAuthenticationResponse::class);
    }
}
