<?php
namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;


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
