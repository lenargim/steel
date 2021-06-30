<?php
namespace SiteBundle\Admin\Pages;

use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use SiteBundle\Entity\Pages\CatalogArticle;
use SiteBundle\Entity\Pages\CatalogArticlePage;
use SiteBundle\Entity\Pages\CatalogItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CatalogArticleAdmin extends BaseAdmin
{
    protected $classnameLabel = 'Раздел';


    /**
     * @param FormMapper $formMapper
     * @param $subject
     * @param EntityManagerInterface|null $em
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        self::setSeoTexts($formMapper, false);
        $formMapper
            ->tab("Данные")
            ->with('Данные')
            ->add('image', CroppableImageType::class, [
                'label' => 'Картинка широкая 600x314',
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
                    'minWidth' => 600,
                    'minHeight' => 314,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [                 //optional
                        [
                            'maxWidth' => 600,
                            'maxHeight' => 314,
                            'useAsFieldImage' => true  //optional
                        ]
                    ]
                ]
            ])
            ->add('image2', CroppableImageType::class, [
                'label' => 'Картинка узкая 441x314',
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
            ->getRepository(Pages\CatalogMain::class)
            ->findOneBy(['active' => true]);

        if (
            $mainPage
            && !$object->getParent()
        ) {
            $object->setParent($mainPage);
        }
    }
}
