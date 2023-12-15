<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
// use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            
            // ->add('email', CollectionType::class, [ //EXPL Utilisation d'un CollectionType

            //     'entry_type' => EmailType::class,

            //     'entry_options' => [
            //         'attr' => ['class' => 'email-box']
            //     ]
            // ])

            ->add('pseudo', TextType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Acceptez les CGU pour vous inscrire',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm'],
                
                'constraints' => [                                                      
                    new Length([                                // Pour faire en sorte que le password contienne minimum 12 caractères
                        
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 12 caractères',
                        
                        'max' => 4096                           // Nombre maximum de caractères autorisé par Symfony
                    ]),
                    
                    new Regex([                                 // Pour faire en sorte que le password contienne minimum une majuscule,...
                        
                        'pattern' => "/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{12,}\S*$/",
                        'message' => 'Minimum 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
                    ]),

                ]
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
