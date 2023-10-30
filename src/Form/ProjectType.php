<?php

namespace App\Form;

use App\Entity\Images;
use App\Entity\Projects;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'aria-describedby' => 'clientHelp',
                ]
            ])
            ->add('category', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'aria-describedby' => 'categoryHelp',
                ]
            ])
            ->add('productionPeriod', DateType::class, [
                'label' => false,
                'input' => 'datetime_immutable',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'attr' => [
                    'aria-describedby' => 'productionPeriodHelp',
                    'class' => 'flex-fill'
                ]
            ])
            ->add('work_link', UrlType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'aria-describedby' => 'work_linkHelp',
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'aria-describedby' => 'descriptionHelp',
                    'class' => 'form-textarea',
                    'rows' => 5,
                ]
            ])
            ->add('proj_image', FileType::class, [
                'multiple' => false,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier au format PNG, JPEG, GIF, WebP.',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/png ,image/jpeg, image/webp',
                ]

            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'multiple' => true,
                'choice_label' => 'name',
                'by_reference' => false, // chercher les setteurs avec set ou add utile pour les ManyToMany
                'attr' => [
                    'class' => 'select-tags w-100',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
            'required' => true
        ]);
    }
}
