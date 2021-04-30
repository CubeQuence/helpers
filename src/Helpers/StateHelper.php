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
        $state = $custom ? $custom : Random::string();

        return SessionHelper::set(
            name: 'cq_state',
            data: $state
        );
    }

    /**
     * Validate $provided_state.
     */
    public static function valid(
        string $provided_state,
        bool $unset_state = true
    ): bool {
        $known_state = SessionHelper::get(name: 'cq_state');

        if ($unset_state) {
            SessionHelper::unset(name: 'cq_state');
        }

        if (!$provided_state) {
            return false;
        }

        return $provided_state === $known_state;
    }
}
