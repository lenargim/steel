<?php
namespace SiteBundle\Admin\Pages;


use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CatalogItemAdmin extends ListAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        self::setSeoTexts($formMapper, false);
        $formMapper
            ->tab("Картинки")
            ->with('Картинки')
            ->add('image', CroppableImageType::class, [
                'label' => 'Картинка для карточки 760x600',
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
                    'minWidth' => 760,
                    'minHeight' => 600,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 760,
                            'maxHeight' => 600,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('image2', CroppableImageType::class, [
                'label' => 'Картинка для раздела 441x314',
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
                    'minHeight' => 314,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 441,
                            'maxHeight' => 314,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('announce', null, [
                'label' => 'Подпись для раздела',
                'required' => false,
            ])
            ->end()
            ->end()
            ->tab("Похожие товары")
            ->with('Похожие товары')
            ->add('arr1', ChoiceType::class, [
                'label' => 'Похожие товары',
                'multiple' => true,
                'required' => false,
                'choices' => $em->getRepository(Pages\CatalogItem::class)->getListByModule(),
            ])
            ->end()
            ->end()
    ;
        parent::setConfigureFormFields($formMapper, $subject, $em);
    }
}
