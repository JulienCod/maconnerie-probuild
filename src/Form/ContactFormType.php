<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class,[
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                    'minlength' => '5',
                    'maxlength' => '180',
                ],
                'required' => true,
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75  -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ]
                
            ])
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
            ->add('phoneNumber', TextType::class,[
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                    'minlength' => '5',
                    'maxlength' => '20',
                ],
                'required' => true,
                'label' => 'Numéro de téléphone',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ]
            ])
            ->add('subject', TextType::class,[
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full   text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                    'minlength' => '5',
                    'maxlength' => '255',
                ],
                'required' => true,
                'label' => 'Objet',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75  -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ]
            ])
            ->add('content', TextareaType::class,[
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                    'minlength' => '100',
                    'maxlength' => '1000',
                ],
                'required' => true,
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ],
                'constraints' => [
                    new Assert\NotBlank(message:"Le champ nom / prénom ne peut pas être vide"),
                ],
            ])
            ->add('securityQuestion', TextType::class, [
                'label' => 'Quelle est la somme de 2 + 3 ?', // Changez la question en fonction de votre choix
                'attr' => [
                    'class' => 'block py-2.5 px-0 w-full text-brun_terreux bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer',
                ],
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\EqualTo([
                        'value' => '5', // Réponse attendue
                        'message' => 'La réponse à la question de sécurité est incorrecte.',
                    ])
                ],
            ])
            ->add('honeypot', HiddenType::class, [
                'required' => false,
                'attr' => [
                    'style' => 'display:none;', // Assurez-vous qu'il est caché visuellement
                ],
            ])
            ->add('rgpd', CheckboxType::class,[
                'attr' => [
                    'class' => 'w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-brun_terreux text-brun_terreux',
                ],
                'required' => true,
                'label' => 'RGPD',
                'label_attr' => [
                    'class' => 'peer-focus:font-medium absolute font-bold text-lg text-brun_terreux duration-300 transform -translate-y-6 scale-75 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
