<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;


class UserCrudController extends AbstractCrudController
{

public function __construct(
    private MailerInterface $mailer,
    private UserPasswordHasherInterface $passwordHasher
) {
}
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('email'),
            TextField::new('password')->onlyOnForms(),
            BooleanField::new('isActive', 'Actif'),

            
            

            
        ];
    }

       

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if (!$entityInstance instanceof User) {
        return;
    }

    $plainPassword = bin2hex(random_bytes(4));

    $entityInstance->setPassword(
        $this->passwordHasher->hashPassword($entityInstance, $plainPassword)
    );

    parent::persistEntity($entityManager, $entityInstance);

    $email = (new Email())
        ->from('vitegourmandeadmin@gmail.com')
        ->to($entityInstance->getEmail())
        ->subject('Votre compte ViteGourmande a été créé')
        ->html("
            <h2>Bienvenue sur ViteGourmande</h2>
            <p>Votre compte a été créé par un administrateur.</p>
            <p><strong>Email :</strong> {$entityInstance->getEmail()}</p>
            <p><strong>Mot de passe temporaire :</strong> {$plainPassword}</p>
            <p>Connectez-vous puis changez votre mot de passe.</p>
        ");

    $this->mailer->send($email);
}
    
}
