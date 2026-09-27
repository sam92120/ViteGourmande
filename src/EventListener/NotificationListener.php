<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class NotificationListener
{
    #[AsEventListener(event: 'Notification')]
    public function onNotification($event): void
    {
        // ...
    }
}
