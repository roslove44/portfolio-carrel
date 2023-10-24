<?php

namespace App\Form;

use App\Entity\Projects;
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
                            'image/gif',
                            'image/bmp',
                            'image/tiff',
                            'image/webp',
                            'image/svg+xml',
                            'image/x-icon',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier au format PNG, JPEG, GIF, BMP, TIFF, WebP, SVG ou ICO.',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/png ,image/jpeg, image/gif, image/bmp, image/tiff, image/webp, image/svg+xml, image/x-icon',
                ]

            ])
            ->add('tags');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
