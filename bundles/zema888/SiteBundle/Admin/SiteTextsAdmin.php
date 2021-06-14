<?php
namespace SiteBundle\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class SiteTextsAdmin extends AbstractAdmin
{

    protected $context = 'default';
    protected $classnameLabel = 'Тексты сайта';

    /**
     * Конфигурация отображения записи
     *
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title', null, array('label' => 'Название'))
            ->add('alias', null, array('label' => 'Код'))
            ->add('value', null, array('label' => 'Значение'));
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
            ->addIdentifier('title', null, ['label' => 'Название'])
            ->addIdentifier('alias', null, array('label' => 'Код'))
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
                    // 'show' => array(),
                    'edit' => array(),
                    //'delete' => array(),
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
            ->add('title', null, [
                'label' => 'Название',
                'required' => true,
            ])
            ->add('alias', null, [
                'label' => 'Код',
                'required' => false,
                'disabled'  => true,
            ])
            ->add('value', CKEditorType::class, [
                'label' => 'Текст',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ]);
    }

    public function prePersist($obj)
    {

    }

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
        $collection->remove('export');
    }


}