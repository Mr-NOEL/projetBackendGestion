<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Vetement;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class VetementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('descriptif', TextType::class, array(
            'constraints' => [
                new Assert\NotBlank(['message' => 'Saisir le descriptif du vetement']),
                new Assert\Length(['min' => 2, 'minMessage' => '(v2) 2 caractères minimum pour le descriptif']),
            ],
            'required' => true,
        ))
            ->add('type', EntityType::class, array(
                'required' => true,
                'class' => Type::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Sélectionnez un vetement'
            ))
            ->add('prixDeBase', NumberType::class, array(
                'constraints' => [
                    new Assert\NotBlank(['message' => '(v2) Saisir le prix du vetement']),
                    new Assert\Length(['min' => 2, 'minMessage' => '(v2) 2 caractère minimum pour le vetement']),
                    new Assert\Type(['type' => 'numeric', 'message' => '(v2) La valeur saisie n\'est pas un chiffre']),
                    new Assert\Regex([
                        'pattern' => "/^[0-9]{1,}\.{0,1}[0-9]{0,}$/", 'message' => "(v2) Seulement un entier positif."
                    ])
                ],
                'required' => true,
            ))
            ->add('taille', NumberType::class, array(
                'constraints' => [
                    new Assert\NotBlank(['message' => '(v2) Saisir la taille du vetement']),
                    new Assert\Length(['min' => 2, 'minMessage' => '(v2) 2 caractère minimum pour la taille du vetement']),
                    new Assert\Type(['type' => 'numeric', 'message' => '(v2) La valeur saisie n\'est pas un chiffre']),
                    new Assert\Regex([
                        'pattern' => "/^[0-9]{1,}\.{0,1}[0-9]{0,}$/", 'message' => "(v2) Seulement un entier positif."
                    ])
                ],
                'required' => true,
            ))
            ->add('dateAchat', DateType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Saisir une date']),
                ],
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => ['placeholder' => 'jj/mm/aaaa'],
            ])
            ->add('Valider', SubmitType::class);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vetement::class,
        ]);
    }
}
