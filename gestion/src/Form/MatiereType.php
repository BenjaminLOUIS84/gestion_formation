<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('denomination',TextType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class, 
                'attr' => ['class' => 'form-control'],  // Particlarité ici le type à besoin d'un tableau d'arguments pour fonctionner
                'choice_label' => 'titre'])

            ->add('valider', SubmitType::class, [       // Ajouter directement le bouton submit ici
            'attr' =>['class' => 'btn btn-dark']])   // Ajouter après class ['attr' =>['class' =>'btn btn-succes']] Pour améliorer le bouton
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
