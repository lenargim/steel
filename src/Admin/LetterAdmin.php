<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LetterAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Письма';
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('status',null, ['label' => 'Статус',], ChoiceType::class, [
                'label' => 'Статус',
                'required' => false,
                'choices' => [
                    'Новый' => 1,
                    'В обработке' => 2,
                    'Завершенный' => 3,
                ]
            ])
            ->add('typeform',null, ['label' => 'Тип формы',], ChoiceType::class, [
                'label' => 'Тип формы',
                'required' => false,
                'choices' => [
                    'Обратная связь' => 'Обратная связь',
                    'Сделать заказ' => 'Сделать заказ',
                ]
            ])
            ->add('name', null, [
                'label' => 'Имя',
                'required' => false,
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'required' => false,
            ])
        ;
    }

    /**
     * Конфигурация списка записей
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('name', null, [
                'label' => 'Имя'
            ])
            ->add('phone', null, [
                'label' => 'Телефон'
            ])
            ->add('email', null, [
                'label' => 'email'
            ])
            ->add('adding1', null, [
                'label' => 'Источник'
            ])
            ->add('typeform', null, [
                'label' => 'Тип формы'
            ])
            ->add('status', 'choice', array(
                'label' => 'Статус',
                'editable' => true,
                'choices' => [
                    1 => 'Новый',
                    2 => 'В обработке',
                    3 => 'Завершенный',
                ]
            ))
            ->add('createdAt', 'datetime', array(
                'format' => 'H:i d-m-y',
                'locale' => 'ru',
                'timezone' => 'Asia/Yekaterinburg',
                'label' => 'Дата создания'
            ))
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
                    // 'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * Конфигурация формы редактирования записи
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id', TextType::class, [
                'label' => 'Номер заказа',
                'required' => false,
                'attr' => ['readonly' => true]
            ])
            ->add('name', null, [
                'label' => 'Имя',
                'required' => false,
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'required' => false,
            ])
            ->add('email', null, [
                'label' => 'email',
                'required' => false,
            ])
            ->add('adding1', null, [
                'label' => 'Источник'
            ])
            ->add('adding2', null, [
                'label' => 'Предмет заказа'
            ])
            ->add('adding3', null, [
                'label' => 'Компания'
            ])
            ->add('typeform', ChoiceType::class, [
                'label' => 'Тип формы',
                'required' => false,
                'choices' => [
                    'Обратная связь' => 'Обратная связь',
                    'Сделать заказ' => 'Сделать заказ',
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'format' => 'H:i d-m-y',
                'label' => 'Дата',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'required' => false,
                'choices' => [
                    'Новый' => 1,
                    'В обработке' => 2,
                    'Завершенный' => 3,
                ]
            ])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('export');
        $collection->remove('create');
        $collection->remove('remove');
        $collection->remove('delete');
    }
}