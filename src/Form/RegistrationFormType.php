<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full   text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                    'minlength' => '5',
                    'maxlength' => '180',
                ],
                'required' => true,
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75  -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ]
            ])
            ->add('password', PasswordType::class,[
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full   text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                    'minlength' => '5',
                    'maxlength' => '180',
                ],
                'required' => true,
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75  -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
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
