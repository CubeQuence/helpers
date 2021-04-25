<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Helpers\Models\SessionModel;
use CQ\OAuth\Models\UserModel;

final class AuthHelper
{
    public static function login(UserModel $user): string
    {
        $returnTo = SessionHelper::get(name: 'return_to');

        // Reset Session
        SessionHelper::reset();

        // Create session
        $session = new SessionModel(
            expiresAt: time() + ConfigHelper::get(key: 'auth.session_lifetime'),
            inactivityTimeout: ConfigHelper::get(key: 'auth.session_timeout')
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
        return $returnTo ? $returnTo : '/dashboard';
    }

    public static function logout(): void
    {
        $invalidSession = new SessionModel(
            expiresAt: 0,
            inactivityTimeout: 0
        );

        SessionHelper::set(
            name: 'session',
            data: $invalidSession
        );
    }

    public static function isValid(): bool
    {
        $session = self::getSession();

        return $session->isValid();
    }

    public static function getUser(): UserModel
    {
        return SessionHelper::get(name: 'user');
    }

    public static function getSession(): SessionModel
    {
        $invalidSession = new SessionModel(
            expiresAt: 0,
            inactivityTimeout: 0
        );

        return SessionHelper::get(name: 'session') ?: $invalidSession;
    }
}
