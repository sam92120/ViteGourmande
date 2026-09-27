<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class HoraireListener
{
    #[AsEventListener(event: 'Horaire')]
    public function onHoraire($event): void
    {
        // ...
    }
}
