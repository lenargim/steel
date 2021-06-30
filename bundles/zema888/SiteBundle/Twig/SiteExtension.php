<?php
namespace SiteBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use SiteBundle\Entity\Settings;
use SiteBundle\Entity\SiteStrings;
use SiteBundle\Entity\SiteTexts;
use SiteBundle\Helper\DataHandler;
use SiteBundle\Repository\SiteStringsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Node\Node;

class SiteExtension extends AbstractExtension
{
    protected EntityManagerInterface $em;
    protected SiteStringsRepository $stringRepo;
    protected UrlGeneratorInterface $generator;

    /**
     * SiteExtension constructor.
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(
        EntityManagerInterface $em,
        UrlGeneratorInterface $generator
    )
    {
        $this->em = $em;
        $this->generator = $generator;
        $this->stringRepo = $this->em->getRepository(SiteStrings::class);
    }


    public function getFilters()
    {
        return array(
            new TwigFilter('mainHundred', array($this, 'mainHundred')),
            new TwigFilter('mainThousand', array($this, 'mainThousand')),
            new TwigFilter('num2String', array($this, 'num2String')),
            new TwigFilter('getImplode', array($this, 'getImplode')),
            new TwigFilter('getText', array($this, 'getText')),
            new TwigFilter('getString', array($this, 'getString')),
            new TwigFilter('getSetting', array($this, 'getSetting')),
            new TwigFilter('ru_RU', array($this, 'ruRU')),
            new TwigFilter('dayweek', array($this, 'dayweek')),
            new TwigFilter('urlTranslite', array($this, 'getUrlTranslite')),
            new TwigFilter('pageById', array($this, 'pageById')),
            new TwigFilter('getPage', array($this, 'getPage')),
            new TwigFilter('pageByDiscr', array($this, 'pageByDiscr')),
            new TwigFilter('monthYear', array($this, 'getMonthYear')),
            new TwigFilter('pagesByIds', array($this, 'pagesByIds')),
            new TwigFilter('getPage', array($this, 'getPage')),
            new TwigFilter('vichFileInfo', array($this, 'getVichFileInfo')),
            new TwigFilter('defaultTitle', array($this, 'defaultTitle')),
            new TwigFilter('defaultDescription', array($this, 'defaultDescription')),
            new TwigFilter('countProducts', array($this, 'countProducts')),
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('sitePath', [$this, 'sitePath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }

    /**
     * Генерит страницы
     *
     * @param $type
     * @return Pages
     */
    public function getPage($type): Pages
    {
        $page = new Pages();
        switch ($type) {
            case 'login':
                $page->setTitle($this->getString('Title', 'Страница входа'));
                $page->setDescription($this->getString('Description', 'Страница входа'));
                $page->setKeywords($this->getString('Keywords', 'Страница входа'));
                $page->setH1($this->getString('H1', 'Страница входа'));
                $page->setMenutitle($this->getString('Заголовок меню', 'Страница входа'));
                $page->setRoute('fos_user_security_login');
                break;
        }
        return $page;
    }


    /**
     * @param Pages $page
     * @return int
     */
    public function countProducts(Pages $page): int
    {
        return $this->em->getRepository(Pages\CatalogItemPage::class)->countRecursiveByParent($page);

    }

    public function defaultTitle($page, Request $request)
    {
        if ($page->getTitle()) {
            return $page->getTitle();
        }
        return $this->em->getRepository(Settings::class)->renderMetaTemplate('defaultTitle', $page, []);

    }

    public function defaultDescription($page, $request)
    {
        if ($page->getDescription()) {
            return $page->getDescription();
        }
        return $this->em->getRepository(Settings::class)->renderMetaTemplate('defaultDescription', $page, []);

    }


    /**
     * Массив страниц по массиву ИД
     * @param $ids
     * @return array|object[]|Pages[]
     */
    public function pagesByIds($ids)
    {
        return $ids ? $this->em->getRepository(Pages::class)->findBy(['id' => $ids]) : [];
    }

    /**
     * Сраница по ИД (для всяких связанных страниц без связи в БД)
     *
     * @param int $id
     * @return null|Pages
     */
    public function pageById( int $id): ?Pages
    {
        return $this->em->getRepository(Pages::class)->find($id);
    }

    /**
     * Сраница по ИД  Модуля
     *
     * @param $discr
     * @return null|Pages
     */
    public function pageByDiscr($discr): ?Pages
    {
        return $this->em->getRepository(Pages::$discrClass[$discr])->findOneBy(['active' => true]);
    }

    public function getImplode($arr, $field)
    {
        return '"' . implode('","', array_map(function ($elem) use ($field) {
                return $elem[$field];
            }, $arr)) . '"';
    }

    public function mainHundred($number)
    {
        $number = intval($number%1000);
        return array_map('intval', str_split($number));
    }

    public function mainThousand($number)
    {
        $number = intval($number/1000);
        return array_map('intval', str_split($number));
    }

    public function num2String($num)
    {
        if ($num < 10) {
            return '0' . $num;
        }
        return '' . $num;
    }

    public function getName()
    {
        return 'app_extension';
    }


    public function getString($ruText, $group = 'Общие', $vars = [])
    {
        $str = $this->stringRepo->getString($ruText, $group, 'Symfony\Component\Form\Extension\Core\Type\TextType');
        return $this->setVariables($str, $vars);
    }


    public function getText($ruText, $group = '', $vars = [])
    {
        $str = $this->stringRepo->getString($ruText, $group, 'FOS\CKEditorBundle\Form\Type\CKEditorType');
        return $this->setVariables($str, $vars);
    }

    public function getSetting($alias)
    {
        return $this->em->getRepository(Settings::class)->getSetting($alias);
    }

    public function ruRU($locale)
    {
        $langs = [
            'ru' => 'ru_RU',
            'en' => 'en_US',
        ];
        return isset($langs[$locale]) ? $langs[$locale] : $langs['ru'];
    }

    public function getMonthYear($date)
    {
        $month = [
            '1' => 'Январь',
            '2' => 'Февраль',
            '3' => 'Март',
            '4' => 'Апрель',
            '5' => 'Май',
            '6' => 'Июнь',
            '7' => 'Июль',
            '8' => 'Август',
            '9' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        return sprintf('%s %s', $month[$date->format('n')], $date->format('Y'));
    }

    public function dayweek($number, $locale)
    {
        $days = [
            'ru' => [
                1 => 'Понедельник',
                2 => 'Вторник',
                3 => 'Среда',
                4 => 'Четверг',
                5 => 'Патница',
                6 => 'Суббота',
                7 => 'Воскресенье',
            ],
            'en' => [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ]
        ];

        return isset($days[$locale][$number]) ? $days[$locale][$number] : '';
    }


    public function getUrlTranslite($str)
    {
        return DataHandler::urlTranslit($str);
    }


    /**
     * Замена переменных в тексте
     *
     * @param $text
     * @param array $datas
     * @return mixed
     */
    protected function setVariables($text, $datas)
    {
        $parents = [];
        $replaces = [];
        foreach ($datas as $key => $value) {
            $parents[] = '/[$][$]' . strtoupper($key) . '[$][$]/';
            $replaces[] = $value;
        }
        return preg_replace($parents, $replaces, $text);
    }


    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $relative
     *
     * @return string
     */
    public function sitePath($name, $parameters = [], $relative = false)
    {
        try {
            return $this->generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
        } catch (RouteNotFoundException $e) {
            // TODO если роут из страниц
            return '#';
        }
    }


    /**
     * Determines at compile time whether the generated URL will be safe and thus
     * saving the unneeded automatic escaping for performance reasons.
     *
     * The URL generation process percent encodes non-alphanumeric characters. So there is no risk
     * that malicious/invalid characters are part of the URL. The only character within an URL that
     * must be escaped in html is the ampersand ("&") which separates query params. So we cannot mark
     * the URL generation as always safe, but only when we are sure there won't be multiple query
     * params. This is the case when there are none or only one constant parameter given.
     * E.g. we know beforehand this will be safe:
     * - path('route')
     * - path('route', {'param': 'value'})
     * But the following may not:
     * - path('route', var)
     * - path('route', {'param': ['val1', 'val2'] }) // a sub-array
     * - path('route', {'param1': 'value1', 'param2': 'value2'})
     * If param1 and param2 reference placeholder in the route, it would still be safe. But we don't know.
     *
     * @param Node $argsNode The arguments of the path/url function
     *
     * @return array An array with the contexts the URL is safe
     *
     * @final
     */
    public function isUrlGenerationSafe(Node $argsNode)
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
        $argsNode->hasNode(1) ? $argsNode->getNode(1) : null
        );

        if (null === $paramsNode || $paramsNode instanceof ArrayExpression && \count($paramsNode) <= 2 &&
            (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof ConstantExpression)
        ) {
            return ['html'];
        }

        return [];
    }

    public function getVichFileInfo($file)
    {
        if ($file && $file->getDoc()) {
            $extKey = array_search($file->getDoc()->getMimeType(), $this->mimeTypesMap);
            if ($extKey !== false) {
                $ext = $extKey;
            } else {
                $ext = 'pdf';
            }

            if ($file->getDoc()->getSize() > 1024*1024) {
                $size = $this->numberFormatLocale($file->getDoc()->getSize() / (1024*1024)) . ' Mb';
            } else {
                $size = ceil($file->getDoc()->getSize() / 1024) . ' Kb';

            }
            return [
                'ext' => $ext,
                'size' => $size
                ];
        }
        return [
            'ext' => '',
            'size' => ''
        ];

    }

    protected $mimeTypesMap = array(
        '3dm' => 'x-world/x-3dmf', '3dmf' => 'x-world/x-3dmf', 'a' => 'application/octet-stream', 'aab' => 'application/x-authorware-bin',
        'aam' => 'application/x-authorware-map', 'aas' => 'application/x-authorware-seg', 'abc' => 'text/vnd.abc', 'acgi' => 'text/html',
        'afl' => 'video/animaflex', 'ai' => 'application/postscript', 'aif' => 'audio/aiff', 'aif' => 'audio/x-aiff',
        'aifc' => 'audio/aiff', 'aifc' => 'audio/x-aiff', 'aiff' => 'audio/aiff', 'aiff' => 'audio/x-aiff',
        'aim' => 'application/x-aim', 'aip' => 'text/x-audiosoft-intra', 'ani' => 'application/x-navi-animation', 'aos' => 'application/x-nokia-9000-communicator-add-on-software',
        'aps' => 'application/mime', 'arc' => 'application/octet-stream', 'arj' => 'application/arj', 'arj' => 'application/octet-stream',
        'art' => 'image/x-jg', 'asf' => 'video/x-ms-asf', 'asm' => 'text/x-asm', 'asp' => 'text/asp',
        'asx' => 'application/x-mplayer2', 'asx' => 'video/x-ms-asf', 'asx' => 'video/x-ms-asf-plugin', 'au' => 'audio/basic',
        'au' => 'audio/x-au', 'avi' => 'application/x-troff-msvideo', 'avi' => 'video/avi', 'avi' => 'video/msvideo',
        'avi' => 'video/x-msvideo', 'avs' => 'video/avs-video', 'bcpio' => 'application/x-bcpio', 'bin' => 'application/mac-binary',
        'bin' => 'application/macbinary', 'bin' => 'application/octet-stream', 'bin' => 'application/x-binary', 'bin' => 'application/x-macbinary',
        'bm' => 'image/bmp', 'bmp' => 'image/bmp', 'bmp' => 'image/x-windows-bmp', 'boo' => 'application/book',
        'book' => 'application/book', 'boz' => 'application/x-bzip2', 'bsh' => 'application/x-bsh', 'bz' => 'application/x-bzip',
        'bz2' => 'application/x-bzip2', 'c' => 'text/plain', 'c' => 'text/x-c', 'c++' => 'text/plain',
        'cat' => 'application/vnd.ms-pki.seccat', 'cc' => 'text/plain', 'cc' => 'text/x-c', 'ccad' => 'application/clariscad',
        'cco' => 'application/x-cocoa', 'cdf' => 'application/cdf', 'cdf' => 'application/x-cdf', 'cdf' => 'application/x-netcdf',
        'cer' => 'application/pkix-cert', 'cer' => 'application/x-x509-ca-cert', 'cha' => 'application/x-chat', 'chat' => 'application/x-chat',
        'class' => 'application/java', 'class' => 'application/java-byte-code', 'class' => 'application/x-java-class', 'com' => 'application/octet-stream',
        'com' => 'text/plain', 'conf' => 'text/plain', 'cpio' => 'application/x-cpio', 'cpp' => 'text/x-c',
        'cpt' => 'application/mac-compactpro', 'cpt' => 'application/x-compactpro', 'cpt' => 'application/x-cpt', 'crl' => 'application/pkcs-crl',
        'crl' => 'application/pkix-crl', 'crt' => 'application/pkix-cert', 'crt' => 'application/x-x509-ca-cert', 'crt' => 'application/x-x509-user-cert',
        'csh' => 'application/x-csh', 'csh' => 'text/x-script.csh', 'css' => 'application/x-pointplus', 'css' => 'text/css',
        'cxx' => 'text/plain', 'dcr' => 'application/x-director', 'deepv' => 'application/x-deepv', 'def' => 'text/plain',
        'der' => 'application/x-x509-ca-cert', 'dif' => 'video/x-dv', 'dir' => 'application/x-director', 'dl' => 'video/dl',
        'dl' => 'video/x-dl', 'doc' => 'application/msword', 'dot' => 'application/msword', 'dp' => 'application/commonground',
        'drw' => 'application/drafting', 'dump' => 'application/octet-stream', 'dv' => 'video/x-dv', 'dvi' => 'application/x-dvi',
        'dwf' => 'drawing/x-dwf', 'dwf' => 'model/vnd.dwf', 'dwg' => 'application/acad', 'dwg' => 'image/vnd.dwg',
        'dwg' => 'image/x-dwg', 'dxf' => 'application/dxf', 'dxf' => 'image/vnd.dwg', 'dxf' => 'image/x-dwg',
        'dxr' => 'application/x-director', 'el' => 'text/x-script.elisp', 'elc' => 'application/x-bytecode.elisp', 'elc' => 'application/x-elc',
        'env' => 'application/x-envoy', 'eps' => 'application/postscript', 'es' => 'application/x-esrehber', 'etx' => 'text/x-setext',
        'evy' => 'application/envoy', 'evy' => 'application/x-envoy', 'exe' => 'application/octet-stream', 'f' => 'text/plain',
        'f' => 'text/x-fortran', 'f77' => 'text/x-fortran', 'f90' => 'text/plain', 'f90' => 'text/x-fortran',
        'fdf' => 'application/vnd.fdf', 'fif' => 'application/fractals', 'fif' => 'image/fif', 'fli' => 'video/fli',
        'fli' => 'video/x-fli', 'flo' => 'image/florian', 'flx' => 'text/vnd.fmi.flexstor', 'fmf' => 'video/x-atomic3d-feature',
        'for' => 'text/plain', 'for' => 'text/x-fortran', 'fpx' => 'image/vnd.fpx', 'fpx' => 'image/vnd.net-fpx',
        'frl' => 'application/freeloader', 'funk' => 'audio/make', 'g' => 'text/plain', 'g3' => 'image/g3fax',
        'gif' => 'image/gif', 'gl' => 'video/gl', 'gl' => 'video/x-gl', 'gsd' => 'audio/x-gsm',
        'gsm' => 'audio/x-gsm', 'gsp' => 'application/x-gsp', 'gss' => 'application/x-gss', 'gtar' => 'application/x-gtar',
        'gz' => 'application/x-compressed', 'gz' => 'application/x-gzip', 'gzip' => 'application/x-gzip', 'gzip' => 'multipart/x-gzip',
        'h' => 'text/plain', 'h' => 'text/x-h', 'hdf' => 'application/x-hdf', 'help' => 'application/x-helpfile',
        'hgl' => 'application/vnd.hp-hpgl', 'hh' => 'text/plain', 'hh' => 'text/x-h', 'hlb' => 'text/x-script',
        'hlp' => 'application/hlp', 'hlp' => 'application/x-helpfile', 'hlp' => 'application/x-winhelp', 'hpg' => 'application/vnd.hp-hpgl',
        'hpgl' => 'application/vnd.hp-hpgl', 'hqx' => 'application/binhex', 'hqx' => 'application/binhex4', 'hqx' => 'application/mac-binhex',
        'hqx' => 'application/mac-binhex40', 'hqx' => 'application/x-binhex40', 'hqx' => 'application/x-mac-binhex40', 'hta' => 'application/hta',
        'htc' => 'text/x-component', 'htm' => 'text/html', 'html' => 'text/html', 'htmls' => 'text/html',
        'htt' => 'text/webviewhtml', 'htx' => 'text/html', 'ice' => 'x-conference/x-cooltalk', 'ico' => 'image/x-icon',
        'idc' => 'text/plain', 'ief' => 'image/ief', 'iefs' => 'image/ief', 'iges' => 'application/iges',
        'iges' => 'model/iges', 'igs' => 'application/iges', 'igs' => 'model/iges', 'ima' => 'application/x-ima',
        'imap' => 'application/x-httpd-imap', 'inf' => 'application/inf', 'ins' => 'application/x-internett-signup', 'ip' => 'application/x-ip2',
        'isu' => 'video/x-isvideo', 'it' => 'audio/it', 'iv' => 'application/x-inventor', 'ivr' => 'i-world/i-vrml',
        'ivy' => 'application/x-livescreen', 'jam' => 'audio/x-jam', 'jav' => 'text/plain', 'jav' => 'text/x-java-source',
        'java' => 'text/plain', 'java' => 'text/x-java-source', 'jcm' => 'application/x-java-commerce', 'jfif' => 'image/jpeg',
        'jfif' => 'image/pjpeg', 'jfif-tbnl' => 'image/jpeg', 'jpe' => 'image/jpeg', 'jpe' => 'image/pjpeg',
        'jpeg' => 'image/jpeg', 'jpeg' => 'image/pjpeg', 'jpg' => 'image/jpeg', 'jpg' => 'image/pjpeg',
        'jps' => 'image/x-jps', 'js' => 'application/x-javascript', 'jut' => 'image/jutvision', 'kar' => 'audio/midi',
        'kar' => 'music/x-karaoke', 'ksh' => 'application/x-ksh', 'ksh' => 'text/x-script.ksh', 'la' => 'audio/nspaudio',
        'la' => 'audio/x-nspaudio', 'lam' => 'audio/x-liveaudio', 'latex' => 'application/x-latex', 'lha' => 'application/lha',
        'lha' => 'application/octet-stream', 'lha' => 'application/x-lha', 'lhx' => 'application/octet-stream', 'list' => 'text/plain',
        'lma' => 'audio/nspaudio', 'lma' => 'audio/x-nspaudio', 'log' => 'text/plain', 'lsp' => 'application/x-lisp',
        'lsp' => 'text/x-script.lisp', 'lst' => 'text/plain', 'lsx' => 'text/x-la-asf', 'ltx' => 'application/x-latex',
        'lzh' => 'application/octet-stream', 'lzh' => 'application/x-lzh', 'lzx' => 'application/lzx', 'lzx' => 'application/octet-stream',
        'lzx' => 'application/x-lzx', 'm' => 'text/plain', 'm' => 'text/x-m', 'm1v' => 'video/mpeg',
        'm2a' => 'audio/mpeg', 'm2v' => 'video/mpeg', 'm3u' => 'audio/x-mpequrl', 'man' => 'application/x-troff-man',
        'map' => 'application/x-navimap', 'mar' => 'text/plain', 'mbd' => 'application/mbedlet', 'mc$' => 'application/x-magic-cap-package-1.0',
        'mcd' => 'application/mcad', 'mcd' => 'application/x-mathcad', 'mcf' => 'image/vasa', 'mcf' => 'text/mcf',
        'mcp' => 'application/netmc', 'me' => 'application/x-troff-me', 'mht' => 'message/rfc822', 'mhtml' => 'message/rfc822',
        'mid' => 'application/x-midi', 'mid' => 'audio/midi', 'mid' => 'audio/x-mid', 'mid' => 'audio/x-midi',
        'mid' => 'music/crescendo', 'mid' => 'x-music/x-midi', 'midi' => 'application/x-midi', 'midi' => 'audio/midi',
        'midi' => 'audio/x-mid', 'midi' => 'audio/x-midi', 'midi' => 'music/crescendo', 'midi' => 'x-music/x-midi',
        'mif' => 'application/x-frame', 'mif' => 'application/x-mif', 'mime' => 'message/rfc822', 'mime' => 'www/mime',
        'mjf' => 'audio/x-vnd.audioexplosion.mjuicemediafile', 'mjpg' => 'video/x-motion-jpeg', 'mm' => 'application/base64', 'mm' => 'application/x-meme',
        'mme' => 'application/base64', 'mod' => 'audio/mod', 'mod' => 'audio/x-mod', 'moov' => 'video/quicktime',
        'mov' => 'video/quicktime', 'movie' => 'video/x-sgi-movie', 'mp2' => 'audio/mpeg', 'mp2' => 'audio/x-mpeg',
        'mp2' => 'video/mpeg', 'mp2' => 'video/x-mpeg', 'mp2' => 'video/x-mpeq2a', 'mp3' => 'audio/mpeg3',
        'mp3' => 'audio/x-mpeg-3', 'mp3' => 'video/mpeg', 'mp3' => 'video/x-mpeg', 'mpa' => 'audio/mpeg',
        'mpa' => 'video/mpeg', 'mpc' => 'application/x-project', 'mpe' => 'video/mpeg', 'mpeg' => 'video/mpeg',
        'mpg' => 'audio/mpeg', 'mpg' => 'video/mpeg', 'mpga' => 'audio/mpeg', 'mpp' => 'application/vnd.ms-project',
        'mpt' => 'application/x-project', 'mpv' => 'application/x-project', 'mpx' => 'application/x-project', 'mrc' => 'application/marc',
        'ms' => 'application/x-troff-ms', 'mv' => 'video/x-sgi-movie', 'my' => 'audio/make', 'mzz' => 'application/x-vnd.audioexplosion.mzz',
        'nap' => 'image/naplps', 'naplps' => 'image/naplps', 'nc' => 'application/x-netcdf', 'ncm' => 'application/vnd.nokia.configuration-message',
        'nif' => 'image/x-niff', 'niff' => 'image/x-niff', 'nix' => 'application/x-mix-transfer', 'nsc' => 'application/x-conference',
        'nvd' => 'application/x-navidoc', 'o' => 'application/octet-stream', 'oda' => 'application/oda', 'omc' => 'application/x-omc',
        'omcd' => 'application/x-omcdatamaker', 'omcr' => 'application/x-omcregerator', 'p' => 'text/x-pascal', 'p10' => 'application/pkcs10',
        'p10' => 'application/x-pkcs10', 'p12' => 'application/pkcs-12', 'p12' => 'application/x-pkcs12', 'p7a' => 'application/x-pkcs7-signature',
        'p7c' => 'application/pkcs7-mime', 'p7c' => 'application/x-pkcs7-mime', 'p7m' => 'application/pkcs7-mime', 'p7m' => 'application/x-pkcs7-mime',
        'p7r' => 'application/x-pkcs7-certreqresp', 'p7s' => 'application/pkcs7-signature', 'part' => 'application/pro_eng', 'pas' => 'text/pascal',
        'pbm' => 'image/x-portable-bitmap', 'pcl' => 'application/vnd.hp-pcl', 'pcl' => 'application/x-pcl', 'pct' => 'image/x-pict',
        'pcx' => 'image/x-pcx', 'pdb' => 'chemical/x-pdb', 'pdf' => 'application/pdf', 'pfunk' => 'audio/make',
        'pfunk' => 'audio/make.my.funk', 'pgm' => 'image/x-portable-graymap', 'pgm' => 'image/x-portable-greymap', 'pic' => 'image/pict',
        'pict' => 'image/pict', 'pkg' => 'application/x-newton-compatible-pkg', 'pko' => 'application/vnd.ms-pki.pko', 'pl' => 'text/plain',
        'pl' => 'text/x-script.perl', 'plx' => 'application/x-pixclscript', 'pm' => 'image/x-xpixmap', 'pm' => 'text/x-script.perl-module',
        'pm4' => 'application/x-pagemaker', 'pm5' => 'application/x-pagemaker', 'png' => 'image/png', 'pnm' => 'application/x-portable-anymap',
        'pnm' => 'image/x-portable-anymap', 'pot' => 'application/mspowerpoint', 'pot' => 'application/vnd.ms-powerpoint', 'pov' => 'model/x-pov',
        'ppa' => 'application/vnd.ms-powerpoint', 'ppm' => 'image/x-portable-pixmap', 'pps' => 'application/mspowerpoint', 'pps' => 'application/vnd.ms-powerpoint',
        'ppt' => 'application/mspowerpoint', 'ppt' => 'application/powerpoint', 'ppt' => 'application/vnd.ms-powerpoint', 'ppt' => 'application/x-mspowerpoint',
        'ppz' => 'application/mspowerpoint', 'pre' => 'application/x-freelance', 'prt' => 'application/pro_eng', 'ps' => 'application/postscript',
        'psd' => 'application/octet-stream', 'pvu' => 'paleovu/x-pv', 'pwz' => 'application/vnd.ms-powerpoint', 'py' => 'text/x-script.phyton',
        'pyc' => 'applicaiton/x-bytecode.python', 'qcp' => 'audio/vnd.qcelp', 'qd3' => 'x-world/x-3dmf', 'qd3d' => 'x-world/x-3dmf',
        'qif' => 'image/x-quicktime', 'qt' => 'video/quicktime', 'qtc' => 'video/x-qtc', 'qti' => 'image/x-quicktime',
        'qtif' => 'image/x-quicktime', 'ra' => 'audio/x-pn-realaudio', 'ra' => 'audio/x-pn-realaudio-plugin', 'ra' => 'audio/x-realaudio',
        'ram' => 'audio/x-pn-realaudio', 'ras' => 'application/x-cmu-raster', 'ras' => 'image/cmu-raster', 'ras' => 'image/x-cmu-raster',
        'rast' => 'image/cmu-raster', 'rexx' => 'text/x-script.rexx', 'rf' => 'image/vnd.rn-realflash', 'rgb' => 'image/x-rgb',
        'rm' => 'application/vnd.rn-realmedia', 'rm' => 'audio/x-pn-realaudio', 'rmi' => 'audio/mid', 'rmm' => 'audio/x-pn-realaudio',
        'rmp' => 'audio/x-pn-realaudio', 'rmp' => 'audio/x-pn-realaudio-plugin', 'rng' => 'application/ringing-tones', 'rng' => 'application/vnd.nokia.ringing-tone',
        'rnx' => 'application/vnd.rn-realplayer', 'roff' => 'application/x-troff', 'rp' => 'image/vnd.rn-realpix', 'rpm' => 'audio/x-pn-realaudio-plugin',
        'rt' => 'text/richtext', 'rt' => 'text/vnd.rn-realtext', 'rtf' => 'application/rtf', 'rtf' => 'application/x-rtf',
        'rtf' => 'text/richtext', 'rtx' => 'application/rtf', 'rtx' => 'text/richtext', 'rv' => 'video/vnd.rn-realvideo',
        's' => 'text/x-asm', 's3m' => 'audio/s3m', 'saveme' => 'application/octet-stream', 'sbk' => 'application/x-tbook',
        'scm' => 'application/x-lotusscreencam', 'scm' => 'text/x-script.guile', 'scm' => 'text/x-script.scheme', 'scm' => 'video/x-scm',
        'sdml' => 'text/plain', 'sdp' => 'application/sdp', 'sdp' => 'application/x-sdp', 'sdr' => 'application/sounder',
        'sea' => 'application/sea', 'sea' => 'application/x-sea', 'set' => 'application/set', 'sgm' => 'text/sgml',
        'sgm' => 'text/x-sgml', 'sgml' => 'text/sgml', 'sgml' => 'text/x-sgml', 'sh' => 'application/x-bsh',
        'sh' => 'application/x-sh', 'sh' => 'application/x-shar', 'sh' => 'text/x-script.sh', 'shar' => 'application/x-bsh',
        'shar' => 'application/x-shar', 'shtml' => 'text/html', 'shtml' => 'text/x-server-parsed-html', 'sid' => 'audio/x-psid',
        'sit' => 'application/x-sit', 'sit' => 'application/x-stuffit', 'skd' => 'application/x-koan', 'skm' => 'application/x-koan',
        'skp' => 'application/x-koan', 'skt' => 'application/x-koan', 'sl' => 'application/x-seelogo', 'smi' => 'application/smil',
        'smil' => 'application/smil', 'snd' => 'audio/basic', 'snd' => 'audio/x-adpcm', 'sol' => 'application/solids',
        'spc' => 'application/x-pkcs7-certificates', 'spc' => 'text/x-speech', 'spl' => 'application/futuresplash', 'spr' => 'application/x-sprite',
        'sprite' => 'application/x-sprite', 'src' => 'application/x-wais-source', 'ssi' => 'text/x-server-parsed-html', 'ssm' => 'application/streamingmedia',
        'sst' => 'application/vnd.ms-pki.certstore', 'step' => 'application/step', 'stl' => 'application/sla', 'stl' => 'application/vnd.ms-pki.stl',
        'stl' => 'application/x-navistyle', 'stp' => 'application/step', 'sv4cpio' => 'application/x-sv4cpio', 'sv4crc' => 'application/x-sv4crc',
        'svf' => 'image/vnd.dwg', 'svf' => 'image/x-dwg', 'svr' => 'application/x-world', 'svr' => 'x-world/x-svr',
        'swf' => 'application/x-shockwave-flash', 't' => 'application/x-troff', 'talk' => 'text/x-speech', 'tar' => 'application/x-tar',
        'tbk' => 'application/toolbook', 'tbk' => 'application/x-tbook', 'tcl' => 'application/x-tcl', 'tcl' => 'text/x-script.tcl',
        'tcsh' => 'text/x-script.tcsh', 'tex' => 'application/x-tex', 'texi' => 'application/x-texinfo', 'texinfo' => 'application/x-texinfo',
        'text' => 'application/plain', 'text' => 'text/plain', 'tgz' => 'application/gnutar', 'tgz' => 'application/x-compressed',
        'tif' => 'image/tiff', 'tif' => 'image/x-tiff', 'tiff' => 'image/tiff', 'tiff' => 'image/x-tiff',
        'tr' => 'application/x-troff', 'tsi' => 'audio/tsp-audio', 'tsp' => 'application/dsptype', 'tsp' => 'audio/tsplayer',
        'tsv' => 'text/tab-separated-values', 'turbot' => 'image/florian', 'txt' => 'text/plain', 'uil' => 'text/x-uil',
        'uni' => 'text/uri-list', 'unis' => 'text/uri-list', 'unv' => 'application/i-deas', 'uri' => 'text/uri-list',
        'uris' => 'text/uri-list', 'ustar' => 'application/x-ustar', 'ustar' => 'multipart/x-ustar', 'uu' => 'application/octet-stream',
        'uu' => 'text/x-uuencode', 'uue' => 'text/x-uuencode', 'vcd' => 'application/x-cdlink', 'vcs' => 'text/x-vcalendar',
        'vda' => 'application/vda', 'vdo' => 'video/vdo', 'vew' => 'application/groupwise', 'viv' => 'video/vivo',
        'viv' => 'video/vnd.vivo', 'vivo' => 'video/vivo', 'vivo' => 'video/vnd.vivo', 'vmd' => 'application/vocaltec-media-desc',
        'vmf' => 'application/vocaltec-media-file', 'voc' => 'audio/voc', 'voc' => 'audio/x-voc', 'vos' => 'video/vosaic',
        'vox' => 'audio/voxware', 'vqe' => 'audio/x-twinvq-plugin', 'vqf' => 'audio/x-twinvq', 'vql' => 'audio/x-twinvq-plugin',
        'vrml' => 'application/x-vrml', 'vrml' => 'model/vrml', 'vrml' => 'x-world/x-vrml', 'vrt' => 'x-world/x-vrt',
        'vsd' => 'application/x-visio', 'vst' => 'application/x-visio', 'vsw' => 'application/x-visio', 'w60' => 'application/wordperfect6.0',
        'w61' => 'application/wordperfect6.1', 'w6w' => 'application/msword', 'wav' => 'audio/wav', 'wav' => 'audio/x-wav',
        'wb1' => 'application/x-qpro', 'wbmp' => 'image/vnd.wap.wbmp', 'web' => 'application/vnd.xara', 'wiz' => 'application/msword',
        'wk1' => 'application/x-123', 'wmf' => 'windows/metafile', 'wml' => 'text/vnd.wap.wml', 'wmlc' => 'application/vnd.wap.wmlc',
        'wmls' => 'text/vnd.wap.wmlscript', 'wmlsc' => 'application/vnd.wap.wmlscriptc', 'word' => 'application/msword', 'wp' => 'application/wordperfect',
        'wp5' => 'application/wordperfect', 'wp5' => 'application/wordperfect6.0', 'wp6' => 'application/wordperfect', 'wpd' => 'application/wordperfect',
        'wpd' => 'application/x-wpwin', 'wq1' => 'application/x-lotus', 'wri' => 'application/mswrite', 'wri' => 'application/x-wri',
        'wrl' => 'application/x-world', 'wrl' => 'model/vrml', 'wrl' => 'x-world/x-vrml', 'wrz' => 'model/vrml',
        'wrz' => 'x-world/x-vrml', 'wsc' => 'text/scriplet', 'wsrc' => 'application/x-wais-source', 'wtk' => 'application/x-wintalk',
        'xbm' => 'image/x-xbitmap', 'xbm' => 'image/x-xbm', 'xbm' => 'image/xbm', 'xdr' => 'video/x-amt-demorun',
        'xgz' => 'xgl/drawing', 'xif' => 'image/vnd.xiff', 'xl' => 'application/excel', 'xla' => 'application/excel',
        'xla' => 'application/x-excel', 'xla' => 'application/x-msexcel', 'xlb' => 'application/excel', 'xlb' => 'application/vnd.ms-excel',
        'xlb' => 'application/x-excel', 'xlc' => 'application/excel', 'xlc' => 'application/vnd.ms-excel', 'xlc' => 'application/x-excel',
        'xld' => 'application/excel', 'xld' => 'application/x-excel', 'xlk' => 'application/excel', 'xlk' => 'application/x-excel',
        'xll' => 'application/excel', 'xll' => 'application/vnd.ms-excel', 'xll' => 'application/x-excel', 'xlm' => 'application/excel',
        'xlm' => 'application/vnd.ms-excel', 'xlm' => 'application/x-excel', 'xls' => 'application/excel', 'xls' => 'application/vnd.ms-excel',
        'xls' => 'application/x-excel', 'xls' => 'application/x-msexcel', 'xlt' => 'application/excel', 'xlt' => 'application/x-excel',
        'xlv' => 'application/excel', 'xlv' => 'application/x-excel', 'xlw' => 'application/excel', 'xlw' => 'application/vnd.ms-excel',
        'xlw' => 'application/x-excel', 'xlw' => 'application/x-msexcel', 'xm' => 'audio/xm', 'xml' => 'application/xml',
        'xml' => 'text/xml', 'xmz' => 'xgl/movie', 'xpix' => 'application/x-vnd.ls-xpix', 'xpm' => 'image/x-xpixmap',
        'xpm' => 'image/xpm', 'x-png' => 'image/png', 'xsr' => 'video/x-amt-showrun', 'xwd' => 'image/x-xwd',
        'xwd' => 'image/x-xwindowdump', 'xyz' => 'chemical/x-pdb', 'z' => 'application/x-compress', 'z' => 'application/x-compressed',
        'zip' => 'application/x-compressed', 'zip' => 'application/x-zip-compressed', 'zip' => 'application/zip', 'zip' => 'multipart/x-zip',
        'zoo' => 'application/octet-stream', 'zsh' => 'text/x-script.zsh',
    );

}
