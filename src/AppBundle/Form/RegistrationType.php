<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Форма регистрации пользователя
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', TextType::class, [
            'label' => 'Адрес дома',
            'attr'=> [
                'placeholder' => 'Воронеж, Рабочий проспект, д.100',
            ],
        ]);
        $builder->add('mobileNumber', TextType::class, [
            'label' => 'Мобильный телефон',
            'attr'=> [
                'placeholder' => '+7 (999) 123-45-67',
            ],
        ]);
        $builder->add('username', TextType::class, [
            'label' => 'ФИО',
            'attr'=> [
                'placeholder' => 'Иванов Иван Иванович',
            ],
        ]);
        $builder->add('code', TextType::class, [
            'label' => 'Код из СМС',
            'attr'=> [
                'placeholder' => '9876'
            ],
            'mapped' => false,
        ]);
        
        $builder->remove('email');
        $builder->remove('plainPassword');
        
    }

    public function getParent()
    {
//        return 'fos_user_registration';
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
        
}
