<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Request\Request;

final class CaptchaHelper
{
    /**
     * Validate captcha.
     */
    private static function validate(
        string $url,
        string $secret,
        string $response
    ): bool {
        try {
            $response = Request::send(
                method: 'POST',
                path: $url,
                form: [
                    'secret' => $secret,
                    'response' => $response,
                ]
            );
        } catch (\Throwable) {
            return false;
        }

        return $response?->success ? true : false;
    }

    /**
     * Validate hCaptchaV1.
     */
    public static function hCaptcha(string $secret, string $response): bool
    {
        return self::validate(
            url: 'https://hcaptcha.com/siteverify',
            secret: $secret,
            response: $response
        );
    }

    /**
     * Validate reCaptchaV2.
     */
    public static function v2(string $secret, string $response): bool
    {
        return self::validate(
            url: 'https://www.google.com/recaptcha/api/siteverify',
            secret: $secret,
            response: $response
        );
    }
}
