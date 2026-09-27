<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ContactListener
{
    #[AsEventListener(event: 'Contact')]
    public function onContact($event): void
    {
        // ...
    }
}
