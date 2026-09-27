<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class MenuListener
{
    #[AsEventListener(event: 'Menu')]
    public function onMenu($event): void
    {
        // ...
    }
}
