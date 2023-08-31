<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tags',EntityType::class,[
                'class' => Tags::class, // Remplacez Tag par la classe de votre entité Tag
                'multiple' => true, // Autoriser la sélection de plusieurs tags
                'expanded' => true, // Afficher sous forme de cases à cocher plutôt que d'une liste déroulante
                'choice_label' => 'name',
                'label_attr' =>[
                    'class'  => 'block my-2 text-base underline font-medium text-gray-900 '
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
                ],
                'choice_attr' => function ($tag) {
                    return ['class' => 'bg-blue-100 px-2 py-1 rounded-md']; 
                },
                'label' => 'Tags',
                'required' => false,
            ])
            ->add('categories',EntityType::class,[
                'class' => Categories::class, // Remplacez Category par la classe de votre entité Category
                'multiple' => true, // Autoriser la sélection de plusieurs catégories
                'expanded' => true, // Afficher sous forme de cases à cocher plutôt que d'une liste déroulante
                'choice_label' => 'name',
                'label' => 'Catégories',
                'label_attr' =>[
                    'class'  => 'block mb-2 text-base underline font-medium text-gray-900'
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ],
                 'choice_attr' => function ($categories) {
                    return ['class' => 'bg-blue-100 px-2 py-1 rounded-md'];
                },
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        
    }
}
