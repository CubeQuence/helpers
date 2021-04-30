<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\Crypto\Random;

final class StateHelper
{
    /**
     * Set state.
     */
    public static function set(string $custom = ''): string
    {
        return SessionHelper::set(
            name: 'cq_state',
            data: $custom ?: Random::string()
        );
    }

    /**
     * Validate provided state.
     */
    public static function isValid(
        string $providedState,
        bool $unsetState = true
    ): bool {
        $sessionState = SessionHelper::get(name: 'cq_state');

        if ($unsetState) {
            SessionHelper::unset(name: 'cq_state');
        }

        if (!$providedState) {
            return false;
        }

        return $providedState === $sessionState;
    }
}
