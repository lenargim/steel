<?php
namespace SiteBundle\Admin\Pages;


use App\Entity\City;
use App\Services\CityService;
use Comur\ImageBundle\Form\Type\CroppableGalleryType;
use Comur\ImageBundle\Form\Type\CroppableImageType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use SiteBundle\Entity\Pages;
use SiteBundle\Interfaces\SetConfigureFormFieldsInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;

class CatalogItemPageAdmin extends ListAdmin
{
    /**
     * @param FormMapper $formMapper
     * @param $subject
     */
    public static function setConfigureFormFields(FormMapper $formMapper, $subject, ?EntityManagerInterface $em = null)
    {
        $cityList = self::getCityList($em);
        self::setTexts($formMapper, $subject);
        $formMapper
            ->tab("Картинки")
            ->with('Картинки')
            ->add('image', CroppableImageType::class, [
                'label' => 'Картинка (440px x 330px)',
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
                    'minWidth' => 440,
                    'minHeight' => 330,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => [
                        [
                            'maxWidth' => 440,
                            'maxHeight' => 330,
                        ],
                        [
                            'maxWidth' => 318,
                            'maxHeight' => 238,
                        ],
                        [
                            'maxWidth' => 120,
                            'maxHeight' => 90,
                            'useAsFieldImage' => true  //optional
                        ],
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
                    'thumbs' => [
                        [
                            'maxWidth' => 501,
                            'maxHeight' => 445,
                        ],
                        [
                            'maxWidth' => 100,
                            'maxHeight' => 100,
                            'useAsFieldImage' => true  //optional
                        ],
                    ]
                ]
            ])

            ->end()
            ->end()
            ->tab("Настройки")
            ->with('Настройки')
            ->add('popular', null, [
                'label' => 'В популярные товары',
                'required' => false,
            ])
            ->add('onmain', null, [
                'label' => 'На главную',
                'required' => false,
            ])
            ->end()
            ->end()
            ->tab("Параметры")
            ->with('Параметры')
            ->add('price', null, [
                'label' => 'Цена',
                'help' => 'Сейчас не используется, оставлена для просмотра',
                'required' => false,
                'attr' => [
                    'readonly' => true
                ],
            ])
            ->add('mainTitle', null, [
                'label' => 'Подпись для главной',
                'required' => false,
            ])
            ->add('descriptionText', CKEditorType::class, [
                'label' => 'Описание',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ])
            ->add('specification', CKEditorType::class, [
                'label' => 'Тех. характеристики',
                'required' => false,
                'attr' => [
                    'class' => 'ckeditor'
                ]
            ])
            ->end()
            ->end()
            ->tab("Похожие товары")
            ->with('Похожие товары')
            ->add('favorite', ChoiceType::class, [
                'label' => 'Похожие товары',
                'multiple' => true,
                'required' => false,
                'choices' => $em->getRepository(Pages\CatalogItem::class)->getListByModule(),
            ])
            ->end()
            ->end()
            ->tab("Цены")
            ->with('Цены')
            ->add('prices', JsontableType::class, [
                'label' => 'Цены',
                'required' => true,
                'keys' => array_keys($cityList),
                'labeles' => array_values($cityList),
                'fixed_row' => true,
                'min' => 1,
                'max' => 1,
            ])
            ->end()
            ->end()
    ;
        parent::setConfigureFormFields($formMapper, $subject, $em);
    }

    /**
     * @param EntityManagerInterface|null $em
     * @return array
     */
    public static function getCityList(?EntityManagerInterface $em = null): array
    {
        if (!$em) {
            return [];
        }
        $objs = $em->getRepository(City::class)->findAll();
        $res = [];
        /** @var City $obj */
        foreach ($objs as $obj) {
            $res[$obj->getId()] = $obj->getTitle();
        }
        return $res;
    }
}
