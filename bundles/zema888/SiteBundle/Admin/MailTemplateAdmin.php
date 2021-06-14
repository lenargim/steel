<?php
namespace SiteBundle\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MailTemplateAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Шаблоны писем';
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
            ->add('alias', null, array('label' => 'Код'))
            ->add('email', null, array('label' => 'Адрес'));
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
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->addIdentifier('alias', null, array('label' => 'Код'))
            ->addIdentifier('email', null, array('label' => 'Адрес'))
            ->add('_action', 'actions', array(
                'label' => 'Действия',
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
            ->add('name', null, [
                'label' => 'Название',
            ])
            ->add('alias', null, [
                'label' => 'Код',
                'disabled'  => true,
            ])
            ->add('email', null, [
                'label' => 'Email для отправки писем',
            ])
            ->add('subject', null, [
                'label' => 'Тема письма',
            ])
            ->add('variables', TextareaType::class, [
                'label' => 'Вы можете использовать переменные ',
            ])
            ->add('text', CKEditorType::class, [
                'label' => 'Текст письма',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ],
            ])
        ;
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