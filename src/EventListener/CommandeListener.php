<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ExceptionListener
{
    #[AsEventListener(event: 'Commande')]
    public function onCommande($event): void
    {
        // ...
    }
}
