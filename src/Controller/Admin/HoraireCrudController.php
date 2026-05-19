<?php

namespace App\Controller\Admin;

use App\Entity\Horaire;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class HoraireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Horaire::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('jour', 'Jour');

        yield TimeField::new('heureOuverture', 'Heure d’ouverture')
            ->setFormat('HH:mm')
            ->setFormTypeOption('widget', 'single_text');

        yield TimeField::new('heureFermeture', 'Heure de fermeture')
            ->setFormat('HH:mm')
            ->setFormTypeOption('widget', 'single_text');

        yield BooleanField::new('estFerme', 'Fermé');
    }
}