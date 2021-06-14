<?php
namespace SiteBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class RedirectsAdmin extends AbstractAdmin
{
    /**
     * Конфигурация списка записей
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('redirectfrom', null, [
                'label' => 'Откуда'
            ])
            ->addIdentifier('redirectto', null, [
                'label' => 'Куда'
            ])
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'actions' => array(
                    // 'show' => array(),
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
        $formMapper
            ->add('redirectfrom', null, [
                'label' => 'Откуда',
                'required' => true,
            ])
            ->add('redirectto',null, [
                'label' => 'Куда',
                'required' => true,
            ])

        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('export');
    }
}