<?php

declare(strict_types=1);

namespace CQ\Helpers;

use Dotenv\Dotenv;

final class ConfigHelper
{
    private static ConfigHelper | null $instance = null;
    private static array $config = [];

    /**
     * Define project dir.
     */
    private function __construct()
    {
        $appRootPath = AppHelper::getRootPath();
        $configDir = $appRootPath . '/config';

        // Load .env
        $dotenv = Dotenv::createImmutable(
            paths: $appRootPath . '/'
        );
        $dotenv->load();

        // Get all config files
        $configFiles = scandir( // TODO: use cubequence/files
            directory: $configDir
        );

        unset($configFiles[0]); // Removes . entry
        unset($configFiles[1]); // Removes .. entry

        foreach ($configFiles as $configFile) {
            $name = str_replace(
                search: '.php',
                replace: '',
                subject: $configFile
            );

            $configData = require "{$configDir}/{$name}.php";

            self::$config[$name] = $configData;
        }
    }

    /**
     * Get config entry.
     */
    public static function get(string $key, $fallback = null): mixed
    {
        $configSingleton = self::getInstance();
        $config = $configSingleton::$config;

        $value = ArrHelper::get(
            array: $config,
            key: $key,
            default: $fallback
        );

        // Convert string to boolean
        if ($value === 'true' || $value === 'false') {
            return $value === 'true';
        }

        return $value;
    }

    /**
     * Get access to the Config singleton
     */
    private static function getInstance(): ConfigHelper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
