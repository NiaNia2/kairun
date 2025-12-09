<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Objectif;
use App\Entity\Ingredients;
use App\Entity\TypeDeRepas;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('nom', TextType::class, [
                'label' => 'Nom de la recette'
            ])
            ->add('temp_prepa_min', IntegerType::class, [
                'label' => 'Temps de préparation (minutes)'
            ])
            ->add('temps_cuissons_min', IntegerType::class, [
                'label' => 'Temps de cuisson (minutes)'
            ])

            ->add('ingredient', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
            ->add('objectif', EntityType::class, [
                'class' => Objectif::class,
                'choice_label' => 'nom',
                'label' => 'Objectif',
                'placeholder' => 'Choisissez un objectif',
            ])
            ->add('typeDeRepas', EntityType::class, [
                'class' => TypeDeRepas::class,
                'choice_label' => 'nom',
                'label' => 'Type de repas',
                'placeholder' => 'Choisissez un type de repas',
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description ',
                'required' => true,
                'attr' => [
                    'rows' => 6,
                    'placeholder' => 'Décrivez les étapes de votre recette.',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
