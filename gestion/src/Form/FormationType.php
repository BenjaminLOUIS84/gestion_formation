<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule',TextType::class)
            ->add('description',TextType::class)
            ->add('valider', SubmitType::class, [       // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']])   // Ajouter après class ['attr' =>['class' =>'btn btn-succes']] Pour améliorer le bouton
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
