<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class PlatListener
{
    #[AsEventListener(event: 'Plat')]
    public function onPlat($event): void
    {
        // ...
    }
}
