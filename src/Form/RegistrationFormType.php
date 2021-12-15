<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            ->add('Nombre',TextType::class)
            ->add('Apellido',TextType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'label'=> 'Terminos y Condiciones',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Acepta los terminos y condiciones.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Agrega una contraseña',
                    ]),
                    new Length([
                        'min' => 6,'max' => 15,
                    ]),

                    new Regex([
                            
                        'pattern'=>'/^(?=.*[a-z])(?=.*\d).{6,}$/i',
                        'message' =>"La contraseña no contien los siguientes valores /^(?=.*[a-z])(?=.*\d).{6,}$/i"
                    
                    ])
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
