<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageMailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ваше имя',
                'required' => false
            ])
            ->add('typeform', HiddenType::class, [
                'label' => 'Тип формы',
                'required' => false
            ])
            ->add('url', HiddenType::class, [
                'label' => 'Страница',
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'required' => false,
                'attr' => [
                    'class' => 'mask'
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Телефон',
                'required' => false,
                'attr' => [
                    'class' => 'mask'
                ],
            ])
            ->add('policy', CheckboxType::class, [
                'label' => 'С политикой конфиденциальности компании ознакомлен(на)',
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MessageMail::class
        ));
    }
}
