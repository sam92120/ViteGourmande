<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UserListener
{
    #[AsEventListener(event: 'User')]
    public function onUser($event): void
    {
        // ...
    }
}
