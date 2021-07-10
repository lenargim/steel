<?php
namespace SiteBundle\Admin\Pages;

use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;


class MainPageAdmin extends ListAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     * @param EntityManagerInterface|null $em
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {

        self::setSeoTexts($formMapper, false);
        $formMapper
            ->tab('Тексты')
            ->with('')
            ->add('subTitle', null, [
                'label' => 'Подзаголовок',
                'required' => false,
            ])
            ->add('linkTitle', null, [
                'label' => 'Подпись ссылки',
                'required' => false,
            ])
            ->add('link', null, [
                'label' => 'Ссылка',
                'required' => false,
            ])
            ->add('arr1', JsontableType::class, [
                'label' => 'Партнеры',
                'keys' => ['title'],
                'labeles' => ['Название'],
            ])
            ->end()
            ->end()
        ;
        $formMapper
            ->tab('Контакты')
            ->with('Контакты')
            ->add('mapLat', null, [
                'label' => 'Широта',
                'required' => false,
            ])
            ->add('mapLng', null, [
                'label' => 'Долготаи',
                'required' => false,
            ])
            ->add('mapPhone', null, [
                'label' => 'Телефон',
                'required' => false,
            ])
            ->add('mapEmail', null, [
                'label' => 'Email',
                'required' => false,
            ])
            ->add('mapAddress', null, [
                'label' => 'Адрес',
                'required' => false,
            ])
            ->add('mapKey', null, [
                'label' => 'maps.googleapis.com key',
                'help' => 'Токен API google , инструкция ка кподключить: https://developers.google.com/maps/gmp-get-started',
                'required' => false,
            ])
            ->end()
            ->end()
        ;
        parent::setConfigureFormFields($formMapper, $subject, $em);
    }
    /**
     * Установка родителя
     *
     *
     * @param Pages $object
     * @param AbstractAdmin $adminClass
     */
    public static function setParentPage(Pages $object, AbstractAdmin $adminClass)
    {
        $object->setParent(null);
    }
}
