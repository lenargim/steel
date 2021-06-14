<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallbackMailType extends AbstractType
{
    static $formName = 'callback_mail';

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::$formName;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ваше имя',
                'required' => true
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
                'required' => true,
                'attr' => [
                    'class' => 'mask'
                ],
            ])
            ->add('policy', CheckboxType::class, [
                'label' => 'С политикой конфиденциальности компании ознакомлен(на)',
                'required' => false,
                'data' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CallbackMail::class
        ));
    }
}