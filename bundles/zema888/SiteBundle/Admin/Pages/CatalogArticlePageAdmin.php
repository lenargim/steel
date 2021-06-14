<?php
namespace SiteBundle\Admin\Pages;

use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages\CatalogArticle;
use SiteBundle\Entity\Pages\CatalogArticlePage;
use SiteBundle\Entity\Pages\CatalogItem;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CatalogArticlePageAdmin extends ListAdmin
{
    protected $classnameLabel = 'Отрасль';


    public static function currentAdminTemplate(string $name, $subject): ?string
    {
        if ($subject instanceof CatalogArticle) {
            if ($name == 'edit' && !empty($subject->getChildren([CatalogItem::class]))) {
                return 'admin/catalog-article-edit.html.twig';
            }
        }
        return null;
    }

    /**
     * @param FormMapper $formMapper
     * @param $subject
     * @param EntityManagerInterface|null $em
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        parent::setConfigureFormFields($formMapper, $subject, $em);
        $formMapper
            ->tab("Данные")
            ->with('Данные')
            ->add('image', CroppableImageType::class, [
                'label' => 'Картинка 320x190',
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
            ->add('popular', null, [
                'label' => 'В популярные товары',
                'required' => false,
            ])
            ->add('mainTitle', null, [
                'label' => 'Подпись для раздела',
                'required' => false,
            ])
            ->end()
            ->end()
        ;
    }
}
