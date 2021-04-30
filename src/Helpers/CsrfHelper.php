<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Crypto\Random;

final class CsrfHelper
{
    public static function set(): string
    {
        $token = Random::string();

        SessionHelper::set(
            name: 'csrf',
            data: $token
        );

        return $token;
    }

    public static function isValid(string $givenToken): bool
    {
        $sessionToken = SessionHelper::get(name: 'csrf');
        SessionHelper::unset(name: 'csrf');

        if (!$sessionToken) {
            return false;
        }

        return $sessionToken === $givenToken;
    }
}
