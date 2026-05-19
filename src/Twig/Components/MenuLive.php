<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class MenuLive
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $quantite = 10;

    #[LiveAction]
    public function reserver()
    {
        if ($this->quantite > 0) {
            $this->quantite--;
        }
    }
}