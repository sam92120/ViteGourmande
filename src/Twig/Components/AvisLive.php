<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
// #[LiveProp]
// public int $quantite = 10;
final class AvisLive
{
    use DefaultActionTrait;
    //mettre en place une action pour ajouter un avis
}

