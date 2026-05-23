<?php

namespace App\Controller\Admin;

use App\Entity\Plat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class PlatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plat::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titreplat'),
            ImageField::new('photo')
            ->setBasePath('uploads/plats')
            ->setUploadDir('public/uploads/plats')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->setLabel('Photo du plat')
            ->setHelp('Téléchargez une image pour le plat.')
            ->setFormTypeOptions([
                'attr' => ['accept' => 'image/*'],
            ])
            ->onlyOnForms(),
            TextField::new('allergene'),
            TextField::new('type'),

            
            
            
                
        ];
    }
    
   /* public function configureActions(Actions $actions): Actions
    {
    return $actions
        //->disable(Action::DELETE)
        ->disable(Action::BATCH_DELETE);
}*/
}
