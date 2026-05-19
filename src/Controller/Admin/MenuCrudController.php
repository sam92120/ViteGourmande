<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;


class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

           
           yield IdField::new('id')
            ->hideOnForm(),

            yield TextField::new('titre'),

            yield TextEditorField::new('description'),

           yield IntegerField::new('prix_par_pers'),
         yield TextField::new('regime'),
           yield TextField::new('quantite_restante'),
            yield IntegerField::new('nb_pers_min'),
             yield AssociationField::new('theme')
            ->setCrudController(ThemeCrudController::class),
            
            yield AssociationField::new('plats')
            ->setCrudController(PlatCrudController::class),


            yield BooleanField::new('isActive', 'Actif'),
        ];
    }

 /*public function configureActions(Actions $actions): Actions
{
    return $actions
        //->disable(Action::DELETE)
        ->disable(Action::BATCH_DELETE);
        
}*/
    
}