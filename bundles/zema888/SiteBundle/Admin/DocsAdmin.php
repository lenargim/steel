<?php

namespace SiteBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocsAdmin extends AbstractAdmin
{

    protected $classnameLabel = 'Документы';

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title',null, ['label' => 'Название',])
            ->add('page', null, [
                'label' => 'Страница'
            ])
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
            ->addIdentifier('title', null, [
                'label' => 'Название'
            ])
            ->add('page', null, [
                'label' => 'Страница'
            ])
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'move' => [
                        'template' => '@PixSortableBehavior/Default/_sort.html.twig'
                    ],
                ]
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $formMapper
            ->tab("Названия")
            ->with('Названия')
            ->add('title', TextType::class, [
                'label' => 'Название',
                'required' => true
            ])
            ->add('page', ModelListType::class, [
                'label' => 'Страница',
                'required' => true
            ])
            ->add('typeView', ChoiceType::class, [
                'label' => 'Тип отображения',
                'choices' => [
                    'Кнопкой' => 'button',
                    'Ссылкой' => 'link',
                ],
                'empty_data' => 'button',
                'required' => false
            ])
            ->add('position', null, [
                'label' => 'Позиция',
                'required' => true
            ])
            ->add('docFile', VichFileType::class, [
                'label' => 'Файл',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Удалить',
                'download_label' => $subject && $subject->getDoc() ? $subject->getDoc()->getName() .' ( ' . number_format($subject->getDoc()->getSize() / (1024 * 1024), 2) . ' Мб)' : 'Скачать',
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