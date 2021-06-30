<?php

namespace SiteBundle\Admin;

use SiteBundle\Admin\Pages\BaseAdmin;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Route\RouteCollection;
use ZemaTreeBundle\Admin\AbstractTreeAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;

class TreePagesAdmin extends AbstractTreeAdmin
{
    protected $classnameLabel = 'Страницы сайта';

    /**
     * TreePagesAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param $treeTextField
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        $treeTextField
    )
    {
        parent::__construct($code, $class, $baseControllerName, $treeTextField);
    }


    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('discr', ChoiceFilter::class, [
                'label' => 'Тип страницы',
                'choices' => Pages::$types
                ])
            ->add('path', null, ['label' => 'url (без первого /)',])
            ->add('parent', null, [
                'label' => 'Родитель',
                'required' => false,
            ]);
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
            ->addIdentifier('list_title', null, array('label' => 'Заголовок меню'))
            ->add('slug', null, array('label' => 'Путь (URL)'))
            ->add('active', null, array(
                'label' => 'Опубликовано',
                'editable' => true
            ))
            ->add('discr', 'choice', [
                'label' => 'Тип страницы',
                'choices' => Pages::$types
            ])
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
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
        $object = $this->getSubject();
        if ($object) {
            $adminClass = $object->getAdminClass();
            $adminClass::setConfigureFormFields($formMapper, $object, $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager'));
        } else {
            BaseAdmin::setSeoTexts($formMapper);
        }
    }

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'lft'
    );


    public function preUpdate($object)
    {
        $adminClass = $object->getAdminClass();
        $adminClass::basePrepareData($object, $this);
    }

    public function prePersist($object)
    {
        $adminClass = $object->getAdminClass();
        $adminClass::basePrepareData($object, $this);
    }


    public function postUpdate($object)
    {
        $adminClass = $object->getAdminClass();
        $adminClass::basePrepareData($object, $this);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
    }
}
