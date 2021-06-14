<?php
namespace SiteBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SiteBundle\Entity\Module;
use SiteBundle\Entity\Pages;

class SiteFixtures extends Fixture implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Modules
        $modules = $manager->getRepository(Module::class)->findAll();
        if (count($modules) == 0) {
            $modulesData = [
                [
                    'title' => 'Тeкстовая страница',
                    'admin' => 'text',
                    'controller' => 'App:Text:index',
                ],
                [
                    'title' => 'Главная',
                    'admin' => 'main',
                    'controller' => 'App:Main:index',
                ],
                [
                    'title' => 'Каталог товара',
                    'admin' => 'catalog_main',
                    'controller' => 'App:Catalog:main',
                ],
                [
                    'title' => 'Карточка товара',
                    'admin' => 'catalog_item',
                    'controller' => 'App:Catalog:index',
                ],
            ];

            foreach ($modulesData as $moduleData) {
                $module = new Module();
                $module->setTitle($moduleData['title']);
                $module->setAdmin($moduleData['admin']);
                $module->setController($moduleData['controller']);
                $manager->persist($module);
            }
        }
        $manager->flush();
        $pages = $manager->getRepository(Pages::class)->findAll();
        if (count($pages) == 0) {
            $main = new Pages();
            $main->setRoot($main);
            $main->setLft(1);
            $main->setLvl(0);
            $main->setRgt(100);
            $main->setSlug('/');
            $main->setActive(true);
            $main->setModule($manager->getRepository(Module::class)->find(2));
            $main->setTitle('Главная');
            $main->setH1('Главная');
            $main->setMenutitle('Главная');
            $main->setDescription('Главная');
            $main->setKeywords('Главная');
            $main->setTopMenu(0);
            $main->setBottomMenu(0);
            $main->setAnnounce('<p>Самым известным «рыбным» текстом является знаменитый Lorem ipsum. Считается, что впервые его применили в книгопечатании еще в XVI веке. Своим появлением Lorem ipsum обязан древнеримскому философу Цицерону, ведь именно из его трактата «О пределах добра и зла» средневековый книгопечатник вырвал отдельные фразы и слова, получив текст-«рыбу», широко используемый и по сей день. Конечно, возникают некоторые вопросы, связанные с использованием Lorem ipsum на сайтах и проектах, ориентированных на кириллический контент – написание символов на латыни и на кириллице значительно различается.</p>');
            $main->setText('<p>Каждый веб-разработчик знает, что такое текст-«рыба». Текст этот, несмотря на название, не имеет никакого отношения к обитателям водоемов. Используется он веб-дизайнерами для вставки на интернет-страницы и демонстрации внешнего вида контента, просмотра шрифтов, абзацев, отступов и т.д. Так как цель применения такого текста исключительно демонстрационная, то и смысловую нагрузку ему нести совсем необязательно. Более того, нечитабельность текста сыграет на руку при оценке качества восприятия макета.</p>');
            $manager->persist($main);
        }

        $manager->flush();
    }

}