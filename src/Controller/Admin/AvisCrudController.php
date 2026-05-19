<?php

namespace App\Controller\Admin;
use App\Entity\Avis;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AvisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user', 'Utilisateur');
        yield IntegerField::new('note', 'Note');
        yield TextareaField::new('commentaire', 'Avis');
        yield BooleanField::new('isApproved', 'Validé')
            ->renderAsSwitch(false);
    }

    
    #[AdminRoute(path: '/valider-avis', name: 'valider_avis')]
    public function validerAvis(AdminContext $context)
    {
        $avis = $context->getEntity()->getInstance();

        $avis->setIsApproved(true);

        $entityManager = $this->container->get(EntityManagerInterface::class);
        $entityManager->flush();

        $this->addFlash('success', 'Avis validé');

        return $this->redirect($this->generateUrl('admin'));
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('note')
            ->add('isApproved');
    }

   
public function configureActions(Actions $actions): Actions
{
    $valider = Action::new('validerAvis', 'Valider', 'fa fa-check')
        ->linkToCrudAction('validerAvis')
        ->setCssClass('btn btn-success')
        ->displayIf(fn (Avis $avis) => !$avis->isApproved());

    return $actions
        ->add(Crud::PAGE_INDEX, $valider)
        ->add(Crud::PAGE_DETAIL, $valider);
}
    
}