<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('numeroCommande'),
            DateField::new('dateCommande'),
            DateField::new('datePretation'),
            TimeField::new('heureLivraison'),
            NumberField::new('prixMenu'),
            IntegerField::new('nbPers'),
            NumberField::new('prixLivraison'),

            AssociationField::new('menu'),
            AssociationField::new('user'),

            ChoiceField::new('status', 'Statut')
                ->setChoices([
                    'En attente' => 'en_attente',
                    'Commande validée' => 'validee',
                    'En préparation' => 'preparation',
                    'En cours de livraison' => 'livraison',
                    'Livrée' => 'livree',
                    'Matériel prêté' => 'materiel_prete',
                    'Matériel restitué' => 'materiel_restitue',
                    'Annulée' => 'annulee',
                ])
                ->renderExpanded()
                ->setRequired(true),

                //TextareaField::new('pretMateriel', 'Matériel prêté')->hideOnIndex(),
                //TextareaField::new('restiMateriel', 'Matériel restitué')->hideOnIndex(),


        ChoiceField::new('modeContactAnnulation', 'Mode de contact pour annulation')
                ->setChoices([
                'Appel GSM' => 'appel GSM',
                'Mail' => 'mail',
    ])
    ->hideOnIndex()

        ];
    

    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if (!$entityInstance instanceof Commande) {
        return;
    }

    parent::updateEntity($entityManager, $entityInstance);

    $message = match ($entityInstance->getStatus()) {
        'en_attente' => 'Votre commande est en attente.',
        'validee' => 'Votre commande a été validée.',
        'preparation' => 'Votre commande est en préparation.',
        'livraison' => 'Votre commande est en cours de livraison.',
        'livree' => 'Votre commande a été livrée.',
        'materiel_prete' => 'Le matériel a été prêté.',
        'materiel_restitue' => 'Le matériel a été restitué.',
        'annulee' => 'Votre commande a été annulée.',
        default => 'Le statut de votre commande a changé.',
    };

    $notification = new Notification();
    $notification->setUser($entityInstance->getUser());
    $notification->setCommande($entityInstance);
    $notification->setMessage($message);
    $notification->setIsRead(false);
    $notification->setCreatedAt(new \DateTimeImmutable());

    $entityManager->persist($notification);
    $entityManager->flush();
}


/*public function configureActions(Actions $actions): Actions
{
    return $actions
        //->disable(Action::DELETE)
        ->disable(Action::BATCH_DELETE);
}*/



public function configureFilters(Filters $filters): Filters
{
    return $filters
        ->add(ChoiceFilter::new('statut')
            ->setChoices([
                'Accepté' => 'accepté',
                'En préparation' => 'en préparation',
                'En cours de livraison' => 'en cours de livraison',
                'Livré' => 'livré',
                'En attente du retour de matériel' => 'en attente du retour de matériel',
                'Terminée' => 'terminée',
                'Annulée' => 'annulée',
            ]))
        ->add(EntityFilter::new('user', 'Client'));
}
}