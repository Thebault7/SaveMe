<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Votre image : ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Ajoutez une image au format .jpg/.jpeg/.png',
                    ])
                ],
            ])
            ->add('nom', TextType::class, ['label' => 'Nom du fichier : '])
            //  ->add('url')
            ->add('description', TextType::class, ['label' => 'Description : ', 'required'=>false])
            // ->add('date', TextType::class, ['label'=> 'Nom du fichier : '])
            ->add('lieu', TextType::class, ['label' => 'Lieu : ','required'=>false])
            //  ->add('proprietaire')
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'libelle', 'label' => 'Catégorie : '])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer' , 'attr' => ['id' => 'bouton']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Photo::class,
            ]);
    }
}