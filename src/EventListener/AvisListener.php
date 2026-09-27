<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AvisListener
{
    #[AsEventListener(event: 'Avis')]
    public function onAvis($event): void
    {
        // ...
    }
}
