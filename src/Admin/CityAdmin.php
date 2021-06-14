<?php

namespace App\Admin;


use App\Services\UpdatePricesService;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;

class CityAdmin extends AbstractAdmin
{

    protected $classnameLabel = 'Города';

    protected UpdatePricesService $updatePricesService;

    /**
     * CityAdmin constructor.
     * @param UpdatePricesService $updatePricesService
     * @param $code
     * @param $class
     * @param $baseControllerName
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        UpdatePricesService $updatePricesService
    )
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->updatePricesService = $updatePricesService;
    }


    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('title',null, ['label' => 'Название',])
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
                'help' => '$$CITY$$',
                'label' => 'Название'
            ])
            ->add('address', null, [
                'label' => 'Адрес'
            ])
            ->add('domain', null, [
                'label' => 'Хост'
            ])
            ->add('bydefault', null, [
                'label' => 'по-умолчанию',
                'editable' => true
            ])
            ->add('_action', 'actions', array(
                'label' => 'Действия',
                'actions' => array(
                    // 'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab("Названия")
            ->with('Названия')
            ->add('title', null, [
                'help' => '$$CITY$$',
                'label' => 'Название',
                'required' => true
            ])
            ->add('geoCityName', null, [
                'help' => 'Смотреть тут https://www.ip2location.com/ , можно несколько через пробел указывать',
                'label' => 'Название в базе геолокации (регион)',
                'required' => true
            ])
            ->add('titleGen', null, [
                'help' => '$$CITYGEN$$',
                'label' => 'Название родительный падеж',
                'required' => false
            ])
            ->add('titleAccu', null, [
                'help' => '$$CITYACCU$$',
                'label' => 'Название винительный падеж',
                'required' => false
            ])
            ->add('titlePrepos', null, [
                'help' => '$$CITYPREPOS$$',
                'label' => 'Название предложный падеж',
                'required' => false
            ])
            ->add('phone', null, [
                'help' => '$$PHONE$$',
                'label' => 'Телефон'
            ])
            ->add('phone2', null, [
                'label' => 'Телефон2'
            ])
            ->add('address', null, [
                'label' => 'Адрес'
            ])
            ->add('workTime', null, [
                'label' => 'Время работы'
            ])
            ->add('domain', null, [
                'label' => 'Хост'
            ])
            ->add('bydefault', null, [
                'label' => 'по-умолчанию'
            ])
//            ->add('contact', CKEditorType::class, [
//                'label' => 'Текст для страницы контактов',
//                'required' => false,
//                'attr' => [
//                    'class' => 'ckeditor'
//                ]
//            ])
            ->add('headmetrika', null, [
                'label' => 'Блок для счетчиков в head'
            ])
            ->add('yandexmetrika', null, [
                'label' => 'Блок для счетчиков в конце body'
            ])
            ->end()
            ->end()
            ->tab("Контакты")
            ->with('Контакты')

            ->add('points', JsontableType::class, [
                'label' => 'Точки на карте',
                'help' => 'В первой стрчоке координаты центра и масштаб',
                'required' => false,
                'keys' => ['lat', 'lng', 'title'],
                'labeles' => ['Широта', 'Долгота', 'Подпись'],
                'fixed_row' => false,
            ])
            ->add('col1', CKEditorType::class, [
                'label' => 'Левая колонка текста',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ])
            ->add('col2', CKEditorType::class, [
                'label' => 'Правая колонка текста',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ])
            ->end()
            ->end()
        ;
    }

    /**
     * @param object $object
     * @throws \Exception
     */
    public function postPersist($object)
    {
        parent::postPersist($object);
        $this->updatePricesService->execute();
    }

    /**
     * @param object $object
     * @throws \Exception
     */
    public function postRemove($object)
    {
        parent::postRemove($object);
        $this->updatePricesService->execute();
    }


    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'title' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('export');
    }
}
