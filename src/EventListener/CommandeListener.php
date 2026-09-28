<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class CommandeListener 
{
    #[AsEventListener(event: 'Commande')]
    public function onCommande($event): void
    {
        // ...
    }
}
