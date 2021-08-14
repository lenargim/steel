<?php
namespace SiteBundle\Admin\Pages;


use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactsPageAdmin extends BaseAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        self::setSeoTexts($formMapper, true);
        $formMapper
            ->tab('Контакты')
            ->with('Контакты')
            ->add('mapLat', null, [
                'label' => 'Широта',
                'required' => false,
            ])
            ->add('mapLng', null, [
                'label' => 'Долгота',
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
}
