<?php

declare(strict_types=1);

namespace CQ\Helpers;

final class SessionHelper
{
    /**
     * Set session var.
     */
    public static function set(string $name, $data): mixed
    {
        $_SESSION[$name] = $data;

        return $data;
    }

    /**
     * Unset session var.
     */
    public static function unset(string $name): void
    {
        unset($_SESSION[$name]);
    }

    /**
     * Get session var.
     */
    public static function get(string $name): mixed
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * Destroy and reset session.
     */
    public static function reset(): void
    {
        session_destroy();
        session_start();
        session_regenerate_id();
    }
}
