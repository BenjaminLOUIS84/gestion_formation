<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder    //////////////////////////////////////FORMULAIRE PARALELLE/////////////////////////////////////
           
            ->add('session', HiddenType::class) // Pour cacher le champ
            
            ->add('matiere', EntityType::class, [
                'label' => 'Module',
                'class' => Matiere::class, 
                'attr' => ['class' => 'form-control'],                         // Particlarité ici le type à besoin d'un tableau d'arguments pour fonctionner
                'choice_label' => 'denomination'
            ])

            ->add('duree', IntegerType ::class , [ //
                'label' => 'Durée en jours',
                'attr' => ['min' => 1, 'max' => 50]
            ])  

            // ->add('submit', SubmitType::class)                             // Pas besoin de bouton de validation
        ;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
