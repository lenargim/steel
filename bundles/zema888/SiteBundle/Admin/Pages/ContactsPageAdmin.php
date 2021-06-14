<?php
namespace SiteBundle\Admin\Pages;

use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Zema\Bundle\JsontableBundle\Form\Type\JsontableType;


class ContactsPageAdmin extends ListAdmin
{
    protected $classnameLabel = 'Контакты';

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
        parent::setConfigureFormFields($formMapper, $subject, $em);
        $formMapper
            ->tab("Контакты")
            ->with('Контакты')

            ->add('text', null, [
                'label' => 'Левая колонка текста',
                'help' => 'Заполняется в городах',
                'required' => false,
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('announce', null, [
                'label' => 'Правая колонка текста',
                'required' => false,
                'help' => 'Заполняется в городах',
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->end()
            ->end();

    }
}
