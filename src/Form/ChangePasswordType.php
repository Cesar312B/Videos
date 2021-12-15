<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;


class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('password', RepeatedType::class, array(
                'constraints' => [new Length(['min' => 6,'max'=>12]),
                 new Regex([
                    'pattern'=>'/^(?=.*[a-z])(?=.*\d).{6,}$/i',
                    'message' =>"La contraseña no contien los siguientes valores /^(?=.*[a-z])(?=.*\d).{6,}$/i"
                 ])
            ],
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Nueva Contraseña'),
                'second_options' => array('label' => 'Repita la Contraseña')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Employed::class,
        ]);
    }
}
