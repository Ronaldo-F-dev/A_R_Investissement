<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            /*->add('imageFile', VichImageType::class, [
                "label" => false,
                'required' => false,
                'allow_delete' => true,
                'delete_label' => false,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => true,
                //'imagine_pattern' => '...',
                //'asset_helper' => true,
            ])*/

            ->add('email',EmailType::class,[
                "label" => false,
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('nom',TextType::class,[
                "label" => false,
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('prenom',TextType::class,[
                "label" => false,
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('pays',TextType::class,[
                "label" => false,
                "attr" =>[
                    "class" => "form-control"
                ]
            ])
            ->add('numero',TextType::class,[
                "label" => false,
                "attr" =>[
                    "class" => "form-control"
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
