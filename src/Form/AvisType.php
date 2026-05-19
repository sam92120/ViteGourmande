<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('note', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'class' => 'form-control rounded-3',
                ],
            ])

            ->add('commentaire', TextareaType::class, [
                'attr' => [
                    'rows' => 6,
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Laissez votre commentaire ici...',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}