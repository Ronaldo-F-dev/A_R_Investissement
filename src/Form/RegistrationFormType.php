<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class,[
            "attr"=>[
                "class" => "form-control"
            ]
        ])
        ->add('prenom',TextType::class,[
            "attr"=>[
                "class" => "form-control"
            ]
        ])
        ->add('email',EmailType::class,[
            "attr"=>[
                "class" => "form-control"
            ]
        ])
        ->add('pays',TextType::class,[
            "attr"=>[
                "class" => "form-control"
            ]
        ])
        ->add('numero',TextType::class,[
            "attr"=>[
                "class" => "form-control"
            ]
        ])
        
        ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                "label" =>"Mot de passe",
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    "class" => "form-control"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
        ])
        ->add("parrain",TextType::class,[
            "label" => "Code Parrain",
            'mapped' => false,
            "required" => false,
            'attr' =>[
                "class" => "form-control",
                //"required" => false
            ]
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
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
