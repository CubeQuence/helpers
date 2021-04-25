<?php

declare(strict_types=1);

namespace CQ\Helpers;

use CQ\File\Adapters\Providers\Local;
use CQ\File\Folder;
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
        $configDir = '/config';

        $localAdapter = new Local(
            rootPath: $appRootPath
        );

        $folderHandler = new Folder(
            adapterProvider: $localAdapter
        );

        // Load .env
        $dotenv = Dotenv::createImmutable(
            paths: $appRootPath
        );
        $dotenv->load();

        // Get all config files
        $configFiles = $folderHandler->listContents(
            path: $configDir
        );

        // Require config files
        foreach ($configFiles as $configFile) {
            $pathInfo = pathinfo($configFile);
            $configData = require "{$appRootPath}/{$configFile}";

            self::$config[$pathInfo['filename']] = $configData;
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
