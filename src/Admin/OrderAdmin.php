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
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;

class OrderAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Список заказов';
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
            ->addIdentifier('id', null, [
                'label' => 'Номер заказа'
            ])
            ->add('name', null, [
                'label' => 'Имя'
            ])
            ->add('phone', null, [
                'label' => 'Телефон'
            ])
            ->add('email', null, [
                'label' => 'email'
            ])
            ->add('address', null, [
                'label' => 'Адрес'
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
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'email',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Адрес'
            ])
            ->add('price', TextType::class, [
                'label' => 'Сумма',
                'disabled' => true,
            ])
            ->add('status', ChoiceType::class, array(
                'label' => 'Статус',
                'choices' => [
                    'Новый' => 1,
                    'В обработке' => 2,
                    'Завершенный' => 3,
                ]
            ))
            ->add('products', JsontableType::class, [
                'label' => 'Содержимое заказа',
                "required" => false,
                "keys" => [ 'id', 'image', 'menutitle', 'price', 'quantity', 'path'],
                "labeles" => ['ID', 'Картинка', 'Название', 'Цена за шт.', 'Количество', 'URL'],
                "fixed_row" => false
            ])
            ->add('createdAt', DateTimeType::class, [
                'format' => 'H:i d-m-y',
                'label' => 'Дата',
                'required' => false
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