<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BilleterieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
            ])
            // ->add('Email', EmailType::class)
            ->add('artist', TextType::class, [
                'label' => 'Artiste',
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'value'=> "2021-08-20",
                    'min' => "2021-08-20",
                    'max' => "2021-08-22",
                ],
            ])
            ->add('hour', ChoiceType::class, [
                'label' => 'Horaire',
                'choices' => [
                    '16h-18h' => '16h-18h',
                    '18h-20h' => '18h-20h',
                    '21h-23h' => '21h-23h'
                ]
            ])
            ->add('nbOfPlace', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'value' => "1",
                    'min' => "1", 
                    'max' => "100"
                ]
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
