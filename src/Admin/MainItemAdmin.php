<?php


namespace App\Admin;

use Comur\ImageBundle\Form\Type\CroppableImageType;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;


class MainItemAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Товары главной страницы';

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
            ->add('name', null, array('label' => 'Название'))
            ->add('link', null, array('label' => 'Ссылка'))
            ->add('price', null, array('label' => 'Цена'))
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
                'label' => 'Название'
            ])
            ->add('link', null, [
                'label' => 'Ссылка'
            ])
            ->add('price', null, [
                'label' => 'Цена'
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
        $subject = $this->getSubject();
        $formMapper
            ->tab('Настройки')
            ->with('')
            ->add('image', CroppableImageType::class, [
                'label' => 'Логотип (мин 500x528)',
                'required' => false,
                'uploadConfig' => [
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $subject->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $subject->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => false,                      //optional
                ],
                'cropConfig' => [
                    'minWidth' => 500,
                    'minHeight' => 528,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 500,
                            'maxHeight' => 528,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('name', null, [
                'label' => 'Название',
            ])
            ->add('link', null, [
                'label' => 'Ссылка',
            ])
            ->add('price', null, [
                'label' => 'Цена',
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
    }

    public function setPositionService(PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

}
