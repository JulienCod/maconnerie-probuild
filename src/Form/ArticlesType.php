<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900'
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
                ]
            ])
            ->add('content', TextType::class,[
                'label' => 'Contenu',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900'
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
                ]
            ])
            ->add('images', FileType::class,[
                'label' => 'Images',
                'label_attr'=>[
                    'class' => 'block mb-2 text-sm font-medium text-gray-900'
                ],
                'attr' => [
                    'class' => 'block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none'
                ],
                'multiple' => true,
                'mapped'=> false,
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tags::class, // Remplacez Tag par la classe de votre entité Tag
                'multiple' => true, // Autoriser la sélection de plusieurs tags
                'expanded' => false, // Afficher sous forme de cases à cocher plutôt que d'une liste déroulante
                'choice_label' => 'name',
                'label_attr' =>[
                    'class'  => 'block mb-2 text-sm font-medium text-gray-900'
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ],
                'label' => 'Tags',
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class, // Remplacez Category par la classe de votre entité Category
                'multiple' => true, // Autoriser la sélection de plusieurs catégories
                'expanded' => false, // Afficher sous forme de cases à cocher plutôt que d'une liste déroulante
                'choice_label' => 'name',
                'label' => 'Catégories',
                'label_attr' =>[
                    'class'  => 'block mb-2 text-sm font-medium text-gray-900'
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
