<?php


namespace SiteBundle\Admin\Pages;


use App\Entity\Review;
use Comur\ImageBundle\Form\Type\CroppableGalleryType;
use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PortfolioItemPageAdmin extends BaseAdmin
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
                'label' => 'Картинка квадратная (мин 800x800)',
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
                    'minWidth' => 800,
                    'minHeight' => 800,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 800,
                            'maxHeight' => 800,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('image2', CroppableImageType::class, [
                'label' => 'Картинка вертикальная (мин 441x535)',
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
                    'minWidth' => 441,
                    'minHeight' => 535,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 441,
                            'maxHeight' => 535,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('image3', CroppableImageType::class, [
                'label' => 'Картинка горизонтальная (мин 800x535)',
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
                    'minWidth' => 800,
                    'minHeight' => 535,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 800,
                            'maxHeight' => 535,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('arr1', CroppableGalleryType::class, [
                'label' => 'Галерея 441x800',
                'required' => false,
                'uploadConfig' => array(
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => $subject->getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => $subject->getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => false,                      //optional
                    'generateFilename' => true          //optional
                ),
                'cropConfig' => [
                    'minWidth' => 441,
                    'minHeight' => 800,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                            //optional
                        [
                            'maxWidth' => 441,
                            'maxHeight' => 800,
                            'useAsFieldImage' => true  //optional
                        ],
                    ]
                ]
            ])
            ->end()
            ->end()
            ->tab("Данные")
            ->with('Данные')
            ->add('reviewId', ChoiceType::class, [
                'label' => 'Отзыв',
                'choices' => $em->getRepository(Review::class)->getListByModule(),
                'multiple' =>false,
                'required' => false,
            ])
            ->add('onmain', null, [
                'label' => 'На главную',
                'required' => false,
            ])
            ->add('announce', null, [
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
