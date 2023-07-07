<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', Type\EmailType::class)
            ->add('newPassword', Type\PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'empty_data' => '',
                'constraints' => [
                    new Constraints\EqualTo('repeatPassword', message: "Passwords must match."),
                ]
            ])
            ->add('repeatPassword', Type\PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'empty_data' => '',
            ])
            ->add('save', Type\SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
