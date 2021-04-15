<?php

declare(strict_types=1);

namespace CQ\Helpers\Models;

use CQ\Helpers\SessionHelper;

final class SessionModel
{
    private int $updatedAt;

    public function __construct(
        private int $expiresAt = 0,
        private int $inactivityTimeout = 0
    ) {
        SessionHelper::reset();

        $this->updatedAt = time();
    }

    public function isValid(): bool
    {
        $currentTime = time();

        if ($this->inactivityTimeout && $currentTime - $this->updatedAt > $this->inactivityTimeout) {
            return false;
        }

        if ($this->expiresAt && $this->expiresAt < $currentTime) {
            return false;
        }

        // Restart inactivity timeout
        $this->updatedAt = $currentTime;

        return true;
    }
}
