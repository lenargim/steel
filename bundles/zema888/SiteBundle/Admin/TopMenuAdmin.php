<?php

namespace SiteBundle\Admin;

use Comur\ImageBundle\Form\Type\CroppableGalleryType;
use Comur\ImageBundle\Form\Type\CroppableImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;

class TopMenuAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Верхнее меню';

    protected $positionService;

    protected $context = 'pages';


    /**
     * Конфигурация отображения записи
     *
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('page', null, array('label' => 'Страница'))
            ->add('title', null, array('label' => 'Наименование'))
            ->add('link', null, array('label' => 'Страница'))
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
            ->addIdentifier('page', null, [
                'label' => 'Страница'
            ])
            ->add('title', null, [
                'label' => 'Наименование'
            ])
            ->add('link', null, [
                'label' => 'Ссылка',
            ])
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                    'move' => array(
                        'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'
                    ),
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
            ->tab('Настройки')
            ->with('')
            ->add('page', ModelListType::class, [
                'label' => 'Страница',
                'required' => false
            ])
            ->add('title', null, [
                'label' => 'Наименование',
                'help' => 'Для случая когда привязка не к странице, при привязке к странице не используется'
            ])
            ->add('link', null, [
                'label' => 'Ссылка',
                'help' => 'Для случая когда привязка не к странице, при привязке к странице не используется'
            ])
            ->add('position', null, [
                'label' => 'Позиция',
                'required' => true
            ])
            ->end()
            ->end();
    }

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'position' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );


    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
        $collection->remove('export');
//        $collection->remove('create');
//        $collection->remove('delete');
    }

    public function setPositionService(PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

}