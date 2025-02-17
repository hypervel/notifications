<?php

declare(strict_types=1);

namespace LaravelHyperf\Notifications;

use LaravelHyperf\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(dirname(__DIR__) . '/publish/resources/views', 'notifications');
    }
}
