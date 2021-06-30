<?php
namespace SiteBundle\Admin\Pages;


use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CatalogMainAdmin extends ListAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        self::setSeoTexts($formMapper, false);
        $formMapper
            ->tab('Тексты')
            ->with('')
            ->add('announce', null, [
                'label' => 'Текст в списке',
                'required' => false,
            ])
            ->end()
            ->end()
    ;
        parent::setConfigureFormFields($formMapper, $subject, $em);
    }
}
