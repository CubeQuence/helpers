<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Crypto\Models\SymmetricKey;
use CQ\Crypto\Symmetric;

final class AppHelper
{
    public static function getEnvoironment(): string
    {
        return ConfigHelper::get(
            key: 'app.env',
            fallback: 'production'
        );
    }

    public static function isEnvironment(string $check): bool
    {
        return self::getEnvoironment() === $check;
    }

    /**
     * Return if debug is enabled.
     */
    public static function isDebug(): bool
    {
        // Can't debug in production
        if (self::isEnvironment('production')) {
            return false;
        }

        return ConfigHelper::get(
            key: 'app.debug',
            fallback: false
        );
    }

    /**
     * Get project root string
     */
    public static function getRootPath(): string
    {
        [$path] = get_included_files();

        $publicPath = dirname(path: $path);

        $path = str_replace(
            search: '/public', // Remove on unix
            replace: '',
            subject: $publicPath
        );

        return str_replace(
            search: '\public', // Remove on windows
            replace: '',
            subject: $path
        );
    }

    /**
     * Encrypt with appKey
     */
    public static function encrypt(string $string): string
    {
        $appKey = new SymmetricKey(
            encodedKey: ConfigHelper::get('app.key')
        );

        $symmetric = new Symmetric(
            key: $appKey
        );

        return $symmetric->encrypt(string: $string);
    }

    /**
     * Decrypt with appKey
     */
    public static function decrypt(string $encryptedString): string
    {
        $appKey = new SymmetricKey(
            encodedKey: ConfigHelper::get('app.key')
        );

        $symmetric = new Symmetric(
            key: $appKey
        );

        return $symmetric->decrypt(encryptedString: $encryptedString);
    }
}
