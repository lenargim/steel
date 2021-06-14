<?php
namespace SiteBundle\Admin\Pages;

use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ListAdmin extends BaseAdmin
{


    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        self::setConfigureFormFields($formMapper, $subject, $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager'));
    }

    /**
     * @param FormMapper $formMapper
     * @param $subject
     * @param EntityManagerInterface|null $em
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        static::setSeoTexts($formMapper, false);
        parent::setConfigureFormFields($formMapper, $subject, $em);
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
            ->addIdentifier('menutitle', null, [
                'label' => 'Название'
            ])
            ->add('active', null, [
                'label' => 'Опубликовано',
                'editable' => true
            ])
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }



    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('menutitle', null, ['label' => 'Название',])
            ->add('active', null, [
                'label' => 'Опубликовано',
            ]);
    }


    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }
}
