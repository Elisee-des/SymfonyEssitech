<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParentPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mdp', RepeatedType::class, [
                "mapped" => false,
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Nouveau mot de passe"
                ],
                "second_options" => [
                    "label" => "Repeter mot de passe"
                ],
                 "invalid_message" => "mot de passe nom identique",
                 "constraints" => [
                     new Length(null, 6),
                     new NotBlank()
                 ]
            ])
            ->add('Modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
