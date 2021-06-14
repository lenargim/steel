<?php

namespace SiteBundle\Admin\Pages;


use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use SiteBundle\Interfaces\SetConfigureFormFieldsInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BaseAdmin extends AbstractAdmin implements SetConfigureFormFieldsInterface
{
    /**
     * @param string $name
     * @return string|null
     */
    public static function currentAdminTemplate(string $name, $subject): ?string
    {
        return null;
    }

    /**
     * @param FormMapper $formMapper
     * @param Pages $subject
     */
    protected static function setTexts(FormMapper $formMapper, Pages $subject)
    {
        $formMapper
            ->tab('Тексты')
            ->with('')
            ->add('h1', TextType::class, [
                'label' => 'H1',
                'required' => true,
            ])
            ->add('menutitle', TextType::class, [
                'label' => 'Заголовок в меню',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'META title',
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'label' => 'META description',
                'required' => false,
            ])
            ->add('keywords', TextType::class, [
                'label' => 'META keywords',
                'required' => false,
            ])
            ->add('text', CKEditorType::class, [
                'label' => 'Текст',
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
     * @param FormMapper $formMapper
     * @param bool $hideText
     */
    public static function setSeoTexts(FormMapper $formMapper, $hideText = true)
    {
        $formMapper
            ->tab('Тексты')
            ->with('')
            ->add('h1', TextType::class, [
                'label' => 'H1',
                'required' => true,
            ])
            ->add('menutitle', TextType::class, [
                'label' => 'Заголовок в меню',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'META title',
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'label' => 'META description',
                'required' => false,
            ])
            ->add('keywords', TextType::class, [
                'label' => 'META keywords',
                'required' => false,
            ])

        ;
        if (!$hideText) {
            $formMapper
            ->add('seotitle', TextType::class, [
                'label' => 'Стилизованный заголовок',
                'required' => false,
            ])
            ;
        }
        $formMapper
            ->
            add('text', CKEditorType::class, [
                'label' => 'Текст',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ]);
        if (!$hideText) {
            $formMapper
            ->add('announce', CKEditorType::class, [
                'label' => 'SEO текст (подробнее)',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ]);
        }
        $formMapper
            ->end()
            ->end();
    }

    /**
     * @param FormMapper $formMapper
     * @param $subject
     * @param EntityManagerInterface|null $em
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {

        $formMapper
            ->tab("Настройки")
            ->with('Настройки страницы')
            ->add('parent', ModelListType::class, [
                'label' => 'Родитель',
                'required' => $subject->getId() != 1,
                'btn_add' => false,
                'btn_delete' => false,
            ])
            ->add('slug', TextType::class, [
                'label' => 'Псевдоним для URL',
                'required' => false,
            ]);

        if (in_array($subject->getDiscrString(), Pages::methodNonDeleteDiscr())) {
            $subject->setActive(true);
        } else {
            $formMapper
                ->add('active', null, [
                    'label' => 'Опубликовано',
                    'required' => false,
                ]);
        }
        $formMapper
            ->end()
            ->end();
    }


    /**
     * @param Pages $object
     * @param AbstractAdmin $adminClass
     */
    public static function basePrepareData(Pages $object, AbstractAdmin $adminClass)
    {
        foreach ($object->getPagegalleries() as $item) {
            $item->setPage($object);
        }
        foreach ($object->getPageBlocks() as $item) {
            $item->setPage($object);
        }
        foreach ($object->getPageDocs() as $item) {
            $item->setPage($object);
        }

        if (!$object->getParent() && $object->getId() != 1) {
            static::setParentPage($object, $adminClass);
        }
    }

    /**
     * Установка родителя
     *
     *
     * @param Pages $object
     * @param AbstractAdmin $adminClass
     */
    public static function setParentPage(Pages $object, AbstractAdmin $adminClass)
    {
        $mainPage = $adminClass->getConfigurationPool()
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Pages\MainPage::class)
            ->findOneBy(['active' => true]);

        if (
            $mainPage
            && !$object->getParent()
            && $object->getDiscr() != Pages\MainPage::class
        ) {
            $object->setParent($mainPage);
        }
    }

    /**
     * @param $object
     */
    public function preUpdate($object)
    {
        self::basePrepareData($object, $this);
    }

    /**
     * @param $object
     */
    public function prePersist($object)
    {
        self::basePrepareData($object, $this);
    }


    /**
     * @param $object
     */
    public function postUpdate($object)
    {
        self::basePrepareData($object, $this);
    }

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'lft'
    );
}
