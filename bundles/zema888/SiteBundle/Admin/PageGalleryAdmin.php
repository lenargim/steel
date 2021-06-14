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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;

class PageGalleryAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Галлерея страницы';

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
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
                    // 'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'move' => array(
                        'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'
                    ),
                )
            ));
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $formMapper
            ->tab("Настройки")
            ->with('Тексты, страница')
            ->add('page', ModelListType::class, [
                'label' => 'Страница',
                'required' => true
            ])
            ->add('title', TextType::class, [
                'label' => 'Заголовок',
                'required' => true
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Текст',
                'required' => false
            ])
            ->add('button', TextType::class, [
                'label' => 'Подпись кнопки',
                'help' => 'Чтобы отображалась кнопка нужно указать и подпись и ссылку',
                'required' => false
            ])
            ->add('link', TextType::class, [
                'label' => 'Ссылка кнопки',
                'help' => 'Чтобы отображалась кнопка нужно указать и подпись и ссылку',
                'required' => false
            ])
            ->end()
            ->end()
            ->tab("Картинки")
            ->with('Слайд')
            ->add('image', CroppableImageType::class, [
                'label' => 'Слайд (отображаться будет оригинал)',
                'required' => false,
                'uploadConfig' => [
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $this->getRoot()->getSubject()->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $this->getRoot()->getSubject()->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => false,                      //optional
                    'saveOriginal' => 'originalImage',          //optional
                    'generateFilename' => true          //optional
                ],
                'cropConfig' => [
                    'minWidth' => 100,
                    'minHeight' => 100,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [
                        [
                            'maxWidth' => 100,
                            'maxHeight' => 100,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->end()
            ->end()

        ;
    }

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('export');
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
    }

}