<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped'=> false,
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tags::class, // Remplacez Tag par la classe de votre entité Tag
                'multiple' => true, // Autoriser la sélection de plusieurs tags
                'expanded' => false, // Afficher sous forme de cases à cocher plutôt que d'une liste déroulante
                'choice_label' => 'name', 
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class, // Remplacez Category par la classe de votre entité Category
                'multiple' => true, // Autoriser la sélection de plusieurs catégories
                'expanded' => false, // Afficher sous forme de cases à cocher plutôt que d'une liste déroulante
                'choice_label' => 'name', 
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
