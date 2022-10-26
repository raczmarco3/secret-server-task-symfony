<?php

namespace App\Form;

use App\Entity\Secret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddSecretFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('secret', TextType::class, ['label' => 'Secret:', 'attr' => array('placeholder' => 'What is your secret?')])
            ->add('expireAfterView', IntegerType::class, ['label' => 'View count:',
             'attr' => array('placeholder' => 'How many times would you like to visit your secret?')])
            ->add('expireAfter', IntegerType::class, ['label' => 'Expire (in minutes):',
            'attr' => array('placeholder' => 'When should your secret expire?')])
            ->add('add', SubmitType::class, ['label' => 'Submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Secret::class,
        ]);
    }
}
