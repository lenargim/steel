<?php


namespace SiteBundle\Admin\Pages;


use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class InteriorItemPageAdmin extends BaseAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        self::setSeoTexts($formMapper, false);
        $formMapper
            ->tab("Картинки и настройки")
            ->with('Картинки')
            ->add('image', CroppableImageType::class, [
                'label' => 'Картинка для главной (мин 1210x1080)',
                'required' => false,
                'uploadConfig' => [
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $subject->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $subject->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => false,                      //optional
                ],
                'cropConfig' => [
                    'minWidth' => 1210,
                    'minHeight' => 1080,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 1210,
                            'maxHeight' => 1080,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('image1', CroppableImageType::class, [
                'label' => 'Картинка для раздела (мин 500x357)',
                'required' => false,
                'uploadConfig' => [
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $subject->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $subject->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => false,                      //optional
                ],
                'cropConfig' => [
                    'minWidth' => 500,
                    'minHeight' => 357,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 500,
                            'maxHeight' => 357,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->end()
            ->end()
            ->tab("Данные")
            ->with('Данные')
            ->add('company', null, [
                'label' => 'Компания',
                'required' => false,
            ])
            ->add('year', DatePickerType::class, [
                'label' => 'Дата',
                'required' => false,
            ])
            ->add('announce', CKEditorType::class, [
                'label' => 'Текст для раздела',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ])
            ->end()
            ->end()
        ;

        parent::setConfigureFormFields($formMapper, $subject, $em);
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
            ->getRepository(Pages\PortfolioListPage::class)
            ->findOneBy(['active' => true]);

        if (
            $mainPage
            && !$object->getParent()
        ) {
            $object->setParent($mainPage);
        }
    }
}
