<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Objectif;
use App\Entity\Ingredients;
use App\Entity\TypeDeRepas;
use Vich\UploaderBundle\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'required' => false,
                'mapped' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpeg, png, webp)',
                    ])
                ],
            ])
            ->add('nom')
            ->add('temp_prepa_min')
            ->add('temps_cuissons_min')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])

            ->add('description')

            ->add('cree_le', null, [
                'widget' => 'single_text'
            ])

            ->add('objectif', EntityType::class, [
                'class' => Objectif::class,
                'choice_label' => 'id',
            ])
            ->add('typeDeRepas', EntityType::class, [
                'class' => TypeDeRepas::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
