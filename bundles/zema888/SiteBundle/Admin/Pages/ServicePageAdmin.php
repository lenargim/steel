<?php


namespace SiteBundle\Admin\Pages;


use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ServicePageAdmin extends BaseAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        self::setTexts($formMapper, $subject);
        $formMapper
            ->tab("Картинки и настройки")
            ->with('Картинки')
            ->add('image', CroppableImageType::class, [
                'label' => 'Картинка для раздела (мин 320x190)',
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
                    'minWidth' => 320,
                    'minHeight' => 190,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 320,
                            'maxHeight' => 190,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('mainImage', CroppableImageType::class, [
                'label' => 'Картинка Для главной (501px x 445px)',
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
                    'minWidth' => 501,
                    'minHeight' => 445,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 501,
                            'maxHeight' => 445,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('onmain', null, [
                'label' => 'На главную',
                'required' => false,
            ])
            ->add('mainTitle', null, [
                'label' => 'Подпись для раздела',
                'required' => false,
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
            ->getRepository(Pages\ServiceListPage::class)
            ->findOneBy(['active' => true]);

        if (
            $mainPage
            && !$object->getParent()
        ) {
            $object->setParent($mainPage);
        }
    }
}
