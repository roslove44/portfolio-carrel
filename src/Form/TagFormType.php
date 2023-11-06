<?php

namespace App\Form;

use App\Entity\Tags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class TagFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du tag : ',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\-_]+$/', // Expression régulière pour autoriser lettres, chiffres, -, _
                        'message' => 'Le nom du tag ne doit contenir que des lettres, des chiffres, des tirets (-) et des traits de soulignement (_).',
                    ])
                ],
                'attr' => [
                    "aria - describedby" => "tagHelp"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tags::class,
        ]);
    }
}
