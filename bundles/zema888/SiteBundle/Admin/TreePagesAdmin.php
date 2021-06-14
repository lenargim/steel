<?php

namespace SiteBundle\Admin;

use App\Import\Application\Services\ParseService;
use App\Import\Application\Services\UpdateService;
use SiteBundle\Admin\Pages\BaseAdmin;
use SiteBundle\Admin\Pages\TextAdmin;
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
    public ParseService $parseService;
    public UpdateService $updateService;

    /**
     * TreePagesAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param $treeTextField
     * @param ParseService $parseService
     * @param UpdateService $updateService
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        $treeTextField,
        ParseService $parseService,
        UpdateService $updateService
    )
    {
        parent::__construct($code, $class, $baseControllerName, $treeTextField);
        $this->parseService = $parseService;
        $this->updateService = $updateService;
    }

    /**
     * @inheritDoc
     */
    public function getTemplate($name)
    {
        $object = $this->getSubject();
        if ($object) {
            $adminClass = $object->getAdminClass();
            $currentAdminTemplate = $adminClass::currentAdminTemplate($name, $object);
            if ($currentAdminTemplate) {
                return $currentAdminTemplate;
            }
        }

        return $this->getTemplateRegistry()->getTemplate($name);
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
        $collection->add('exportArticle', $this->getRouterIdParameter().'/export-article');
        $collection->add('importArticle', $this->getRouterIdParameter().'/import-article');
    }
}
