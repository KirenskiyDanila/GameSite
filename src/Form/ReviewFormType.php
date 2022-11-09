<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Текст обзора.'
            ])
            ->add('grade', ChoiceType::class, [
                'choices'  => [
                    'Оценка: 1' => 1,
                    'Оценка: 2' => 2,
                    'Оценка: 3' => 3,
                    'Оценка: 4' => 4,
                    'Оценка: 5' => 5,
                    'Оценка: 6' => 6,
                    'Оценка: 7' => 7,
                    'Оценка: 8' => 8,
                    'Оценка: 9' => 9,
                    'Оценка: 10' => 10
                ],
                'attr' => [
                    'class' => 'form-control mb-2',
                ],
                'label' => ' '
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success mb-2'
                ],
                'label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
