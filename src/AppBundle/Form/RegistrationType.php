<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Форма регистрации пользователя
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', null, [
            'label' => 'Адрес дома',
            'attr'=> [
                'placeholder' => 'Воронеж, Рабочий проспект, д.100'
                ]
            ]);
        $builder->add('mobileNumber', null, [
            'label' => 'Мобильный телефон',
            'attr'=> [
                'placeholder' => '+7 (999) 123-45-67'
                ]
        ]);
        $builder->add('username', null, [
            'label' => 'ФИО',
            'attr'=> [
                'placeholder' => 'Иванов Иван Иванович'
                ]
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
