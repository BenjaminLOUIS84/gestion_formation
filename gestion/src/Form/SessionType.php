<?php
// CF Commentaires de StagiaireType & CategorieType
namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text', 'attr' =>['class' =>'form-control']
            
            ])
            
            ->add('date_fin', DateType::class, [
                'widget' => 'single_text', 'attr' =>['class' =>'form-control']
            ])

            ->add('nbMax', IntegerType::class)

//////////////////////////////////////////////////////////////////////////////////////////////////////////COLLECTION TYPE
            
            ->add('programmes', CollectionType::class, [    // La collection attend l'élément qu'elle entrera dans le formulaire mais ce n'est pas obligatoire que ce soit un autre formulaire
                
                'entry_type' => ProgrammeType::class,       // Pour ajouter un autre formulaire

                'prototype' => true,                        // Pour autoriser l'ajout de nouveaux éléments dans l'entité session qui seront persistés grâce aux cascade persist sur l'élément programme 
                                                            // Permet d'activer un data prototype qui sera un élément html qu'on pourra manipuler en JS
                'allow_add' => true,                        // Permet d'ajouter plusieurs éléments
                'allow_delete' => true,                     // Permet de supprimer plusieurs éléments
                
                'by_reference' => false                     // OBLIGATOIRE Car Session n'a pas de setProgramme, c'est Programme qui contient setSession (Programme est propriétaire de la relation)
            ])                                              // Cela évite un mapping false

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            ->add('formation', EntityType::class, [
                'class' => Formation::class, 
                'attr' => ['class' => 'form-control'],      // Particlarité ici le type à besoin d'un tableau d'arguments pour fonctionner
                'choice_label' => 'intitule'])
            

            ->add('valider', SubmitType::class, [           // Ajouter directement le bouton submit ici
                'attr' =>['class' => 'btn btn-dark']
            ])                                              // Ajouter après class ['attr' =>['class' =>'btn btn-succes']] Pour améliorer le bouton
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
