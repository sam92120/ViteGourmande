<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ResetPasswordRequestListener
{
    #[AsEventListener(event: 'ResetPasswordRequest')]
    public function onResetPasswordRequest($event): void
    {
        // ...
    }
}
