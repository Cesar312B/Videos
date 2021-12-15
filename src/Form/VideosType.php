<?php

namespace App\Form;

use App\Entity\Videos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VideosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nombre',TextType::class)
            ->add('Descripcion', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('file',FileType::class,[

                'label' => 'Video MP4',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' =>["video/mp4"],
                        'mimeTypesMessage' => "Wrong File-Typ - please pick a MP4-Video.",
                    ])
                ],
            ])
            ->add('Caratula',FileType::class,[
                'label' => 'Caratula JPG',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

               
                'required' => false,
            

                'constraints' => [
                    new File([
                        'maxSize' => '500k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Porfavor subir una imagen en formato jpg',
                    ])
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videos::class,
        ]);
    }
}
