<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder                                      // Définir le type de champ et importer la classe
            ->add('titre', TextType::class)                
            
            // ->add('titre', CollectionType::class, [   // EXPL Utilisation d'un champs de type collection type
            //     'entry_type' => TextType::class,
            //     'entry_option' => [
            //         'help' => 'You can edit this name here.',
            //     ],
            //     'prototype_options'  => [
            //         'help' => 'You can enter a new name here.',
            //     ],
            // ]) 
            
            ->add('valider', SubmitType::class, [    // Ajouter directement le bouton submit ici
            'attr' =>['class' => 'btn btn-dark']])   // Ajouter après class ['attr' =>['class' =>'btn btn-succes']] Pour améliorer le bouton
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
