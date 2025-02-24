<?php

declare(strict_types=1);

namespace LaravelHyperf\Notifications\Events;

use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use LaravelHyperf\Broadcasting\Contracts\ShouldBroadcast;
use LaravelHyperf\Broadcasting\PrivateChannel;
use LaravelHyperf\Bus\Queueable;
use LaravelHyperf\Notifications\AnonymousNotifiable;
use LaravelHyperf\Notifications\Notification;
use LaravelHyperf\Queue\SerializesModels;

class BroadcastNotificationCreated implements ShouldBroadcast
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param mixed $notifiable the notifiable entity who received the notification
     * @param Notification $notification the notification instance
     * @param array $data the notification data
     */
    public function __construct(
        public mixed $notifiable,
        public Notification $notification,
        public array $data = []
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        if ($this->notifiable instanceof AnonymousNotifiable
            && $this->notifiable->routeNotificationFor('broadcast')
        ) {
            $channels = Arr::wrap($this->notifiable->routeNotificationFor('broadcast'));
        } else {
            $channels = $this->notification->broadcastOn();
        }

        if (! empty($channels)) {
            return $channels;
        }

        if (is_string($channels = $this->channelName())) {
            return [new PrivateChannel($channels)];
        }

        return (new Collection($channels))
            ->map(fn ($channel) => new PrivateChannel($channel))
            ->all();
    }

    /**
     * Get the broadcast channel name for the event.
     */
    protected function channelName(): array|string
    {
        if (method_exists($this->notifiable, 'receivesBroadcastNotificationsOn')) {
            return $this->notifiable->receivesBroadcastNotificationsOn($this->notification);
        }

        $class = str_replace('\\', '.', get_class($this->notifiable));

        return $class . '.' . $this->notifiable->getKey();
    }

    /**
     * Get the data that should be sent with the broadcasted event.
     */
    public function broadcastWith(): array
    {
        if (method_exists($this->notification, 'broadcastWith')) {
            return $this->notification->broadcastWith(); /* @phpstan-ignore-line */
        }

        return array_merge($this->data, [
            'id' => $this->notification->id,
            'type' => $this->broadcastType(),
        ]);
    }

    /**
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return method_exists($this->notification, 'broadcastType')
            ? $this->notification->broadcastType() /* @phpstan-ignore-line */
            : get_class($this->notification);
    }

    /**
     * Get the event name of the notification being broadcast.
     */
    public function broadcastAs(): string
    {
        return method_exists($this->notification, 'broadcastAs')
            ? $this->notification->broadcastAs() /* @phpstan-ignore-line */
            : __CLASS__;
    }
}
