<?php

declare(strict_types=1);

namespace LaravelHyperf\Notifications\Messages;

class DatabaseMessage
{
    /**
     * Create a new database message.
     */
    public function __construct(
        public array $data = []
    ) {
    }
}
