<?php

declare(strict_types=1);

namespace LaravelHyperf\Notifications\Contracts;

use Hyperf\Collection\Collection;

interface Factory
{
    /**
     * Get a channel instance by name.
     */
    public function channel(?string $name = null): mixed;

    /**
     * Send the given notification to the given notifiable entities.
     */
    public function send(array|Collection $notifiables, mixed $notification): void;

    /**
     * Send the given notification immediately.
     */
    public function sendNow(array|Collection $notifiables, mixed $notification): void;
}
