<?php
namespace SiteBundle\Admin;


use SiteBundle\Entity\Settings;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SettingsAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'Настройки';


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, ['label' => 'Наименование'])
            ->add('alias', null, ['label' => 'Псевдоним'])
            ->add('_action', null, [
                'label' => 'Действия',
                'actions' => [
                    'edit' => []
                ]
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        $alias = $subject->getAlias();

        $method = $alias . 'AddFormFields';
        if (method_exists($this, $method)) {
            $this->{$method}($formMapper, $subject);
        }
        $formMapper
            ->add('title', TextType::class, [
                'label' => 'Наименование',
                'disabled' => true,
            ])
            ->add('alias', TextType::class, [
                'label' => 'Псевдоним',
                'disabled' => true,
            ])
            ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
        $collection->remove('export');
        $collection->remove('create');
        $collection->remove('delete');
    }

    protected function headmetrikaAddFormFields(FormMapper $formMapper, Settings $subject)
    {
        $formMapper
            ->add('text', TextareaType::class, [
                'label' => 'Блок для счетчиков в head'
            ]);
    }

    protected function bodymetrikaAddFormFields(FormMapper $formMapper, Settings $subject)
    {
        $formMapper
            ->add('text', TextareaType::class, [
                'label' => 'Блок для счетчиков в конце body'
            ]);
    }


    protected function adminemailAddFormFields(FormMapper $formMapper, Settings $subject)
    {
        $formMapper
            ->add('string', TextType::class, [
                'label' => 'Email администратора сайта'
            ])
        ;
    }


    protected function robotsAddFormFields(FormMapper $formMapper, Settings $subject)
    {
        $formMapper
            ->add('text', TextareaType::class, [
                'label' => 'Содержимое robots.txt без названия хоста'
            ])
        ;
    }

    protected function policyAddFormFields(FormMapper $formMapper, Settings $subject)
    {
        $formMapper
            ->add('docFile', VichFileType::class, [
                'label' => 'Файл с политикой конфиденциальности',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Удалить',
                'download_label' => $subject && $subject->getDoc() ? $subject->getDoc()->getName() .' ( ' . number_format($subject->getDoc()->getSize() / (1024 * 1024), 2) . ' Мб)' : 'Скачать',
            ])
        ;
    }
}
