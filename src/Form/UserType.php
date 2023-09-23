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
            ->add('newPassword', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Passwords must match.',
                'required' => false,
                'first_options'  => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat New Password'],
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
