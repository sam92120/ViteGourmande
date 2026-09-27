<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ThemeListener
{
    #[AsEventListener(event: 'Theme')]
    public function onTheme($event): void
    {
        // ...
    }
}
