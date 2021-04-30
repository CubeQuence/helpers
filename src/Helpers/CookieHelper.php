<?php

declare(strict_types=1);

namespace CQ\Helpers;

final class CookieHelper
{
    public static function set(
        string $name,
        string $value,
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = true,
        bool $httponly = true,
        string $samesite = 'None',
        bool $encrypt = false
    ): bool {
        $options = [
            'expires' => $expires,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite,
        ];

        if ($encrypt) {
            $value = AppHelper::encrypt(
                string: $value
            );
        }

        return setcookie(
            name: $name,
            value: $value,
            expires_or_options: $options
        );
    }

    public static function get(string $name, bool $encrypted = false): string | null
    {
        $value = $_COOKIE[$name] ?? null;

        if ($encrypted) {
            $value = AppHelper::decrypt(
                encryptedString: $value
            );
        }

        return $value;
    }

    public static function delete(string $name): bool
    {
        return self::set(
            name: $name,
            value: '',
            expires: time() - 3600
        );
    }
}
