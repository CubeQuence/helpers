<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Helpers\Models\SessionModel;
use CQ\Helpers\SessionHelper;
use CQ\OAuth\Models\UserModel;

final class AuthHelper
{
    private static function getSession(): SessionModel
    {
        return SessionHelper::get(name: 'session');
    }

    public static function login(UserModel $user): string
    {
        $returnTo = SessionHelper::get(name: 'return_to');

        // Create session
        $session = new SessionModel(
            expiresAt: ConfigHelper::get(key: 'auth.session_timeout'),
            inactivityTimeout: ConfigHelper::get(key: 'auth.session_lifetime')
        );

        // Session info
        SessionHelper::set(
            name: 'session',
            data: $session
        );

        // User info
        SessionHelper::set(
            name: 'user',
            data: $user
        );

        // If returnTo set, redirect to there otherwise to '/dashboard'
        return $returnTo ?: '/dashboard';
    }

    public static function isValid(): bool
    {
        $session = self::getSession();

        return $session->isValid();
    }
}
