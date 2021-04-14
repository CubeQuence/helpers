<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Helpers\ConfigHelper;

final class AuthHelper // TODO: move authlogic to seperate folder
{
    /**
     * Check if session active.
     */
    public static function valid(): bool
    {
        $session = SessionHelper::get(name: 'session');

        if (!SessionHelper::get(name: 'user')) {
            return false;
        }

        if (time() - SessionHelper::get(name: 'last_activity') > ConfigHelper::get(key: 'auth.session_timeout')) {
            return false;
        }

        if (time() - $session['created_at'] > ConfigHelper::get(key: 'auth.session_lifetime')) {
            return false;
        }

        if (time() > $session['expires_at']) {
            return false;
        }

        if ($session['ip'] !== Request::ip() && ConfigHelper::get(key: 'auth.ip_check')) { // TODO: fix
            return false;
        }

        SessionHelper::set(
            name: 'last_activity',
            data: time()
        );

        return true;
    }
}
