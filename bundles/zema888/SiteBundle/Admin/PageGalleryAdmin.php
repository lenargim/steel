<?php

namespace SiteBundle\Admin;

use Comur\ImageBundle\Form\Type\CroppableImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        $subject = $this->getSubject();
        $formMapper
            ->tab("Настройки")
            ->with('Тексты, страница')
            ->add('pageId', ChoiceType::class, [
                'label' => 'Страница',
                'required' => false,
                'choices' => $em->getRepository(Pages\InteriorItemPage::class)->getListByModule(),
            ])
            ->add('title', TextType::class, [
                'label' => 'Год',
                'required' => false
            ])
            ->add(
                'text',
                CKEditorType::class,
                [
                    'label' => 'Текст',
                    'required' => false,
                    'attr' => [
                        'class' => 'ckeditor'
                    ]
                ]
            )
            ->end()
            ->end()
            ->tab("Картинки")
            ->with('Слайд')
            ->add('image', CroppableImageType::class, [
                'label' => 'Слайд (1032Х784)',
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
                    'minWidth' => 1032,
                    'minHeight' => 784,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [
                        [
                            'maxWidth' => 100,
                            'maxHeight' => 100,
                            'useAsFieldImage' => true  //optional
                        ],
                        [
                            'maxWidth' => 1032,
                            'maxHeight' => 784,
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
