<?php
namespace SiteBundle\Admin\Pages;

use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReviewsListPageAdmin extends BaseAdmin
{

    /**
     * @param FormMapper $formMapper
     * @param $subject
     * @param EntityManagerInterface|null $em
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        $formMapper
            ->tab('Тексты')
            ->with('')
            ->add('h1', TextType::class, [
                'label' => 'H1',
                'required' => true,
            ])
            ->add('menutitle', TextType::class, [
                'label' => 'Заголовок в меню',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'META title',
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'label' => 'META description',
                'required' => false,
            ])
            ->add('keywords', TextType::class, [
                'label' => 'META keywords',
                'required' => false,
            ])
            ->end()
            ->end();

        parent::setConfigureFormFields($formMapper, $subject, $em);
    }

}
