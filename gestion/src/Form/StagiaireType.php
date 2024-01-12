<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)                                   // Définir les types de champs et importer les classes
            ->add('prenom', TextType::class)
            ->add('genre', TextType::class)
            ->add('ville', TextType::class)
            ->add('mail', TextType::class)
            ->add('telephone',TextType::class)                              // Ajouter après class ['attr' =>['class' =>'form-control']] Propiété BootStrap pour améliorer l'affichage du texte

            ->add('date_naissance', DateType::class, [                      // Ajouter après class ['widget' => 'single_text', 'attr' =>['class' =>'form-control']] Propiété BootStrap pour améliorer l'affichage de la date
            'widget' =>'single_text', 'attr' =>['class' =>'form-control']] )
            
            ->add('sessions', EntityType::class,[
                'class' => Session::class,
                
                // Pour afficher uniquement les sessions disponibles
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('session')
                    ->where('session.date_debut > CURRENT_DATE()');

                    // ->orderBy('session.date_fin', 'DESC'); Afficher suivant un ordre précis
                },

                'label' => 'Choisissez une formation',
                'multiple' => true,
                'attr' =>['class' =>'form-control'],

                // 'choice_label' => function (Session $session): string {
                //     return $session->getFormation()->getIntitule();
                // },
                // 'expanded' => true

                
            ])

            ->add('valider', SubmitType::class, [                          // Ajouter directement le bouton submit ici
            'attr' =>['class' => 'btn btn-dark']])                      // Ajouter après class ['attr' =>['class' =>'btn btn-succes']] Pour améliorer le bouton
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
