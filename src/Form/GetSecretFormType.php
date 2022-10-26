<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GetSecretFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hash', TextType::class, ['label' => 'Hash:', 'attr' => array('placeholder' => 'Enter your Hash')])
            ->add('options', ChoiceType::class, [
                'label' => 'Chose Content-type:',
                'choices'  => [
                    'application/json' => 'application/json',
                    'application/xml' => 'application/xml'
                ]])
            ->add('add', SubmitType::class, ['label' => 'Confirm'])
        ;
    }
}