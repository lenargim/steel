<?php
namespace App\Controller\Admin;

use App\Import\Application\Services\ParseService;
use App\Import\Application\Services\UpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SiteBundle\Entity\Pages;
use SiteBundle\Helper\DataHandler;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;


class PageCommandController extends AbstractController
{
    /**
     * Запуск пересборки кеша
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/recalc", name="app_main.pages_commands.recalc")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function recalcPages(KernelInterface $kernel)
    {

        $content = [];
        $commands = [
            'site:pages:recalc',
            'site:pages:set_path',
            'site:sitemap:update',
            'cache:clear',
        ];

        $this->run($kernel, 'site:pages:recalc');
        $content[] = 'Дерево пересчитано';

        $this->run($kernel, 'site:pages:set_path');
        $content[] = 'Роутинг пересчитан';

        $content[] = $this->run($kernel, 'site:sitemap:update');

        $this->run($kernel, 'cache:clear');
        $content[] = 'Кеш собран';


        return new Response(implode('<br>', $content));
    }

    /**
     * Сборка кеша
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/cache-clear", name="app_main.pages_commands.cache_clear")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function cacheClear(KernelInterface $kernel)
    {

        $content = [];
        $commands = [
            'cache:clear',
        ];
        foreach ($commands as $command) {
            // return the output, don't use if you used NullOutput()
            $this->run($kernel, $command);
            $content[] = 'Кеш собран';
        }


        return new Response(implode('<br>', $content));
    }

    /**
     * Обновление карты сайта
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/sitemap-update", name="app_main.pages_commands.sitemap_update")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function sitemapUpdate(KernelInterface $kernel)
    {

        $content = [];
        $commands = [
            'site:sitemap:update',
        ];
        foreach ($commands as $command) {
            // return the output, don't use if you used NullOutput()
            $content[] = $this->run($kernel, $command);
        }


        return new Response(implode('<br>', $content));
    }

    /**
     * Перерасчет дерева
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/recalc-tree", name="app_main.pages_commands.recalc_tree")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function recalcTree(KernelInterface $kernel)
    {

        $content = [];
        $commands = [
            'site:pages:recalc',
        ];
        foreach ($commands as $command) {
            $this->run($kernel, $command);
            $content[] = 'Дерево пересчитано';
        }


        return new Response(implode('<br>', $content));
    }

    /**
     * Пересчет роутинга
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/set-path", name="app_main.pages_commands.set_path")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function setPath(KernelInterface $kernel)
    {

        $content = [];
        $commands = [
            'site:pages:set_path',
        ];
        foreach ($commands as $command) {
            $this->run($kernel, $command);
            $content[] = 'Роутинг пересчитан';
        }


        return new Response(implode('<br>', $content));
    }

    /**
     * Запуск одной команд
     * 
     * @param $kernel
     * @param $command
     * @return string
     */
    protected function run($kernel, $command)
    {
        try {
            set_time_limit(0);
            $application = new Application($kernel);
            $application->setAutoExit(false);
            $input = new ArrayInput([
                'command' => $command,
            ]);

            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput();
            $application->run($input, $output);

            // return the output, don't use if you used NullOutput()
            return $output->fetch();
        } catch (\Exception $e) {
            return 'Произошла ошибка: ' . $e->getMessage();
        }
    }

    /**
     * Удаление кеша
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/remove_cache", name="app_main.pages_commands.remove_cache")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function removeCache(KernelInterface $kernel)
    {
        $content = '';
        try {
            $filesystem = new Filesystem();
            $filesystem->remove($kernel->getCacheDir());
            $content .= 'Папка кеша очищена' . PHP_EOL;
        } catch (\Exception $e) {
            $content .= 'При очистке папки кеша произошла ошибка: ' . $e->getMessage() . PHP_EOL;
        }
        die($content);
        return new Response($content);
    }

    /**
     * Активирует все страницы
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/activate-page", name="app_main.pages_commands.activate_page")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function activatePage(KernelInterface $kernel)
    {
        $content = [];
        $commands = [
            'site:site_pages_activate_all',
        ];
        foreach ($commands as $command) {
            $this->run($kernel, $command);
            $content[] = 'Активированны все страницы';
        }


        return new Response(implode('<br>', $content));
    }

    /**
     * Запускает импорт
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/run-import", name="app_main.pages_commands.run_import")
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function runImport(KernelInterface $kernel)
    {
        $content = [];
        $commands = [
            'import:parse' => 'Импорт выполнен',
        ];
        foreach ($commands as $command => $text) {
            $content[] = $this->run($kernel, $command);
        }


        return new Response(implode('<br>', $content));
    }

    /**
     *
     * импорт
     *
     * @Security("is_granted('ROLE_SONATA_ADMIN')")
     * @Route("/pages-commands/import", name="app_main.pages_commands.import")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ParseService $parseService
     * @param UpdateService $updateService
     * @return Response
     */
    public function import(
        Request $request,
        EntityManagerInterface $em,
        ParseService $parseService,
        UpdateService $updateService,
        KernelInterface $appKernel
    )
    {
        $page = new Pages();
        $page->setTitle('Страница импорта');
        $page->setKeywords('Страница импорта');
        $page->setDescription('Страница импорта');
        $page->setRoute('app_main.pages_commands.import"');

        $form = $this->createFormBuilder()
            ->add('category', ChoiceType::class, [
                'label' => 'Родительский раздел каталога',
                'choices' => $em->getRepository(Pages\CatalogArticlePage::class)->getListByModule(),
                'multiple' =>false,
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'label' => 'Файл импорта',
                'required' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Начать импорт'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $importData = $form->getData();
            $importFilePath = $importData['file']->getPathName();
            if ($importFilePath && $importData['category']) {

                $dirPath = $appKernel->getCacheDir() .'/import/';
                $fileName = 'import-' . $importData['category'] . '.csv';
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }
                $importData['file']->move(
                    $dirPath,
                    $fileName
                );
                return $this->render('admin/import-success.html.twig', [
                    'page' => $page,
                ]);
            }
        }
        return $this->render('admin/import.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/robots.txt", name="robots.txt")
     * @param Request $request
     * @return Response
     */
    public function robots(Request $request)
    {
        $host = $request->getHost();
        return $this->render('admin/robots.html.twig', [
            'host' => $host
        ]);
    }

    /**
     * @Route("/sitemap.xml", name="sitemap.xml")
     * @param Request $request
     * @return Response
     */
    public function sitemap(Request $request, KernelInterface $kernel)
    {
        $filePath = $kernel->getProjectDir() . '/public/_sitemap.xml';
        if (!file_exists($filePath) || time() - filemtime($filePath) > 7 * 24 * 3600) {
            $this->sitemapUpdate($kernel);
        }
        $host = $request->getHost();
        $data = preg_replace("/@HOST@/ui", $host, file_get_contents($filePath));
        $response = new Response(
            $data
        );
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
