<?php

namespace IFF\ChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('content', TextType::class, [
                'attr'=> [
                    'placeholder' => 'Введите сообщение...',
                    'class' => 'chat-content',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Отправить',
                'attr'=> [
                    'class' => 'chat-submit',
                ],
            ]);;
    }
    
}
