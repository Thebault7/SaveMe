<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    // // http://localhost/saveme/public/category/1
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       /* $builder
            ->add('libelle', EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'libelle',
                    'label' => 'Filtez selon la catégorie choisie:',
                ]
            );
          //  ->add('save', SubmitType::class, ['label' => 'Filtrer']); */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Category::class,
            ]
        );
    }

}