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

class PageBlocksAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Блоки страницы';

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
            ->add('title', null, array('label' => 'Наименование'))
            ->add('type', null, array('label' => 'Тип'));
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
            ->add('type', null, [
                'label' => 'Тип'
            ])
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
                    // 'show' => array(),
                    'edit' => array(),
                    //'delete' => array(),
//                    'move' => array(
//                        'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'
//                    ),
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
        $subject = $this->getSubject();
        if ($subject) {
            $type = $subject->getType();
            $method = $type . 'AddFormFields';
            if (method_exists($this, $method)) {
                $this->{$method}($formMapper);
            }
        }
        $formMapper
            ->tab('Настройки')
            ->with('')
            ->add('title', null, [
                'label' => 'Наименование'
            ])
            ->add('type', null, [
                'label' => 'Тип',
                'disabled' => true,
                'required' => false
            ])
            ->add('page', ModelListType::class, [
                'label' => 'Страница',
                'required' => false
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
        $collection->remove('create');
        $collection->remove('delete');
    }

    public function setPositionService(PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

    protected function serviceAddFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab("Данные блока")
            ->with('')
            ->add('string1', null, [
                'label' => 'Заголовок',
                'required' => false
            ])
            ->add('string2', null, [
                'label' => 'Подпись кнопки',
                'required' => false
            ])
            ->end()
            ->end()
        ;
    }

    protected function catalogAddFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab("Данные блока")
            ->with('')
            ->add('string1', null, [
                'label' => 'Заголовок',
                'required' => false
            ])
            ->add('string2', null, [
                'label' => 'Подпись кнопки',
                'required' => false
            ])
            ->end()
            ->end()
        ;
    }

    protected function popular_productAddFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab("Данные блока")
            ->with('')
            ->add('string1', null, [
                'label' => 'Заголовок',
                'required' => false
            ])
            ->end()
            ->end()
        ;
    }

    protected function advantageAddFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab("Данные блока")
            ->with('')
            ->add('string1', null, [
                'label' => 'Заголовок',
                'required' => false
            ])
            ->add('arr1', JsontableType::class, [
                'label' => 'Преимущества',
                'required' => false,
                'keys' => ['icon', 'title', 'text'],
                'labeles' => ['Класс иконки', 'Заголовок', 'Тест'],
                'min' => 5,
                'max' => 5,
                'fixed_row' => true,
            ])
            ->end()
            ->end()
        ;
    }

    protected function bannerAddFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab("Данные блока")
            ->with('')
            ->add('string1', null, [
                'label' => 'Заголовок',
                'required' => false
            ])
            ->add('string2', null, [
                'label' => 'Подзаголовок',
                'required' => false
            ])
            ->add('string3', null, [
                'label' => 'Подпись кнопки',
                'required' => false
            ])
            ->end()
            ->end()
        ;
    }

}