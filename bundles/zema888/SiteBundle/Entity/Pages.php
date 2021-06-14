<?php
namespace  SiteBundle\Entity;

use Doctrine\Common\Collections\Collection;
use SiteBundle\Admin\Pages\TextAdmin;
use SiteBundle\Entity\Pages\CartPage;
use SiteBundle\Entity\Pages\CatalogArticle;
use SiteBundle\Entity\Pages\CatalogItem;
use SiteBundle\Entity\Pages\CatalogMain;
use SiteBundle\Entity\Pages\ContactsPage;
use SiteBundle\Entity\Pages\MainPage;
use SiteBundle\Entity\Pages\PolicyPage;
use SiteBundle\Entity\Pages\SearchPage;
use SiteBundle\Entity\Pages\ServiceItem;
use SiteBundle\Entity\Pages\ServiceListPage;
use SiteBundle\Entity\Pages\SuccessOrderPage;
use SiteBundle\Entity\Traits\SeoPageFields;
use SiteBundle\Helper\DataHandler;
use SiteBundle\Entity\Traits\ImageUpload;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use SiteBundle\Interfaces\PageAdminInterface;
use ZemaTreeBundle\Interfaces\NodeInterface;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="pages",indexes={@ORM\Index(columns={"h1", "menutitle", "text"}, flags={"fulltext"})})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\PagesRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name = "discr", type = "string")
 * @ORM\DiscriminatorMap({
 *     "text_page" = "Pages",
 *     "main_page" = "SiteBundle\Entity\Pages\MainPage",
 *     "cart_page" = "SiteBundle\Entity\Pages\CartPage",
 *     "policy_page" = "SiteBundle\Entity\Pages\PolicyPage",
 *     "search_page" = "SiteBundle\Entity\Pages\SearchPage",
 *     "catalog_item" = "SiteBundle\Entity\Pages\CatalogItem",
 *     "catalog_article" = "SiteBundle\Entity\Pages\CatalogArticle",
 *     "catalog_main" = "SiteBundle\Entity\Pages\CatalogMain",
 *     "contacts_page" = "SiteBundle\Entity\Pages\ContactsPage",
 *     "service_item" = "SiteBundle\Entity\Pages\ServiceItem",
 *     "service_list_page" = "SiteBundle\Entity\Pages\ServiceListPage",
 *     "success_order" = "SiteBundle\Entity\Pages\SuccessOrderPage"
 * })
 */
class Pages implements PageAdminInterface, NodeInterface
{
    use ImageUpload;
    use SeoPageFields;
    use Timestampable;


    protected $upload_dir = "page";


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    protected $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Pages")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Pages", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Pages", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;


    /**
     * @ORM\Column(type="integer", options={"default":0}, nullable=true)
     */
    protected $parentId;


    /**
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\PageGallery", mappedBy="page", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $pageGalleries;

    /**
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\PageBlocks", mappedBy="page", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $pageBlocks;
    /**
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\PageDocs", mappedBy="page", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $pageDocs;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    protected $position;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $routerActive;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    protected $subtitle;

    /**
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $arr1;

    /**
     * @var array
     */
    protected $pageBlocksArray = [];

    /**
     * @var null|integer
     */
    protected $recursiveChildrensCount = null;

    /**
     * @var array
     */
    protected $allParentsId = [];

    /**
     * Не удаляемый страницы
     *
     * @return array
     */
    public static function methodNonDeleteDiscr()
    {
        return [
            "main_page",
            "contacts_page",
            "search_page",
            "policy_page",
            "service_list_page",
            "cart_page",
            "catalog_main",
            "success_order",
        ];
    }

    public static $controllers = [
        "text_page" => "App\Controller\TextController::index",
        "main_page" => "App\Controller\MainController::index",
        "policy_page" => "App\Controller\TextController::index",
        "catalog_article" => "App\Controller\CatalogController::list",
        "catalog_item" => "App\Controller\CatalogController::index",
        "catalog_main" => "App\Controller\CatalogController::main",
        "search_page" => "App\Controller\SearchController::index",
        "contacts_page" => "App\Controller\ContactsController::index",
        "service_list_page" => "App\Controller\ServiceController::list",
        "service_item" => "App\Controller\TextController::index",
        "cart_page" => "App\Controller\CartController::index",
        "success_order" => "App\Controller\TextController::index",
    ];

    public static $discrClass = [
        "text_page" => Pages::class,
        "main_page" => MainPage::class,
        "policy_page" => PolicyPage::class,
        "contacts_page" => ContactsPage::class,
        "catalog_article" => CatalogArticle::class,
        "catalog_item" => CatalogItem::class,
        "catalog_main" => CatalogMain::class,
        "search_page" => SearchPage::class,
        "service_list_page" => ServiceListPage::class,
        "service_item" => ServiceItem::class,
        "cart_page" => CartPage::class,
        "success_order" => SuccessOrderPage::class,
    ];

    public static $types = [
        Pages::class => "Текстовая страница",
        MainPage::class => "Главная",
        PolicyPage::class => "Политика конфиденциальности",
        ContactsPage::class => "Контакты",
        CatalogArticle::class => "Каталог раздел",
        CatalogItem::class => "Каталог карточка",
        CatalogMain::class => "Каталог главная",
        SearchPage::class => "Страница поиска",
        ServiceListPage::class => "Список услуг",
        ServiceItem::class => "Страница услуги",
        CartPage::class => "Корзина",
        SuccessOrderPage::class => "Успех заказа",
    ];


    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return TextAdmin::class;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMenutitle() ?:'';
    }

    /**
     * Проверяет является ли id предком
     * @param $id
     * @return bool
     */
    public function isParent($id): bool
    {
        if (!$this->allParentsId) {
            $category = $this;
            while ($category = $category->getParent()) {
                $this->allParentsId[] = $category->getId();
            }
        }
        return in_array($id, $this->allParentsId);
    }


    /**
     * @param int $level
     * @return Pages | null
     */
    public function getParentByLevel(int $level): ?Pages
    {
        $category = $this;
        while ($category && $category->getLvl() > $level) {
            $category = $category->getParent();
        }
        return $category->getLvl() == $level ? $category : null;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children->count() > 0;
    }

    /**
     * @return string
     */
    public function getListTitle()
    {
        $prefix = "";
        for ($i = 1; $i <= $this->lvl; $i++) {
            $prefix .= "---";
        }
        return $prefix.$this->getMenutitle();
    }


    /**
     * @ORM\PrePersist
     */
    public function doPrePersist()
    {
        $this->setPersistData();
    }

    /** @ORM\PostPersist  */
    public function doPostPersist() {
        $this->setPersistData();
    }

    /**
     * Установка дефолтных данных
     */
    protected function setPersistData()
    {

        if (empty($this->active === null)) {
            $this->active = true;
        }

        if (empty($this->topMenu)) {
            $this->topMenu = 0;
        }

        if (empty($this->bottomMenu)) {
            $this->bottomMenu = 0;
        }

        if ((empty($this->h1) || strpos($this->slug, 'New node') !==false) && !empty($this->getMenutitle())) {
            $this->h1 = $this->getMenutitle();
        }

        if ((empty($this->title) || strpos($this->slug, 'New node') !==false) && !empty($this->getMenutitle())) {
            $this->title = $this->getMenutitle();
        }

        if ((empty($this->slug) || strpos($this->slug, 'new-node') !==false)) {
            if (!empty($this->getMenutitle())) {
                $this->slug = DataHandler::urlTranslit($this->getMenutitle());
            } else {
                $this->slug = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 /strlen($x)) )),1,10);
            }
        }
    }


    /**
     * @param array $discrs
     * @return Pages[]
     */
    public function getChildren($discrs = []): array
    {
        return $this->children->filter(function ($item) use ($discrs) {
            return $item->getActive() && (!$discrs || in_array($item->getDiscr(), $discrs));
        })->getValues();
    }

    /**
     * @param $id
     * @return bool
     */
    public function issetChildren($id): bool
    {
        if ($this->getId() == $id) {
            return true;
        }
        $childrens = $this->getChildren();
        if ($childrens) {
            foreach ($childrens as $children) {
                if ($children->getId() == $id || $children->issetChildren($id)) {
                    return true;
                } else {
                    if ($children->issetChildren($id)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Блок по имени
     * @param $type
     * @return mixed
     */
    public function getBlock($type)
    {
        if (!$this->pageBlocksArray) {
            foreach ($this->getPageBlocks() as $item) {
                $this->pageBlocksArray[$item->getType()]  = $item;
            }
        }
        return isset($this->pageBlocksArray[$type]) ? $this->pageBlocksArray[$type] : null;
    }


    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->pageGalleries = new ArrayCollection();
        $this->pageBlocks = new ArrayCollection();
        $this->pageDocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }


    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }



    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setRoot(?self $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }



    public function addChild(Pages $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Pages $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|PageGallery[]
     */
    public function getPageGalleries(): Collection
    {
        return $this->pageGalleries;
    }

    public function addPageGallery(PageGallery $pageGallery): self
    {
        if (!$this->pageGalleries->contains($pageGallery)) {
            $this->pageGalleries[] = $pageGallery;
            $pageGallery->setPage($this);
        }

        return $this;
    }

    public function removePageGallery(PageGallery $pageGallery): self
    {
        if ($this->pageGalleries->contains($pageGallery)) {
            $this->pageGalleries->removeElement($pageGallery);
            // set the owning side to null (unless already changed)
            if ($pageGallery->getPage() === $this) {
                $pageGallery->setPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PageBlocks[]
     */
    public function getPageBlocks(): Collection
    {
        return $this->pageBlocks;
    }

    public function addPageBlock(PageBlocks $pageBlock): self
    {
        if (!$this->pageBlocks->contains($pageBlock)) {
            $this->pageBlocks[] = $pageBlock;
            $pageBlock->setPage($this);
        }

        return $this;
    }

    public function removePageBlock(PageBlocks $pageBlock): self
    {
        if ($this->pageBlocks->contains($pageBlock)) {
            $this->pageBlocks->removeElement($pageBlock);
            // set the owning side to null (unless already changed)
            if ($pageBlock->getPage() === $this) {
                $pageBlock->setPage(null);
            }
        }

        return $this;
    }



    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Pages
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return Collection|PageDocs[]
     */
    public function getPageDocs(): Collection
    {
        return $this->pageDocs;
    }

    public function addPageDoc(PageDocs $pageDoc): self
    {
        if (!$this->pageDocs->contains($pageDoc)) {
            $this->pageDocs[] = $pageDoc;
            $pageDoc->setPage($this);
        }

        return $this;
    }

    public function removePageDoc(PageDocs $pageDoc): self
    {
        if ($this->pageDocs->contains($pageDoc)) {
            $this->pageDocs->removeElement($pageDoc);
            // set the owning side to null (unless already changed)
            if ($pageDoc->getPage() === $this) {
                $pageDoc->setPage(null);
            }
        }

        return $this;
    }

    public function getArr1()
    {
        return $this->arr1;
    }

    public function setArr1($arr1): self
    {
        $this->arr1 = $arr1;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getDiscr()
    {
        return get_class($this);
    }

    public function getModuleTitle() {
        return $this->getDiscrString();
    }

    /**
     * @return mixed
     */
    public function getDiscrString()
    {
        return self::$types[$this->getDiscr()] ?? $this->getDiscr();
    }

    public function getRouterActive(): ?bool
    {
        return $this->routerActive;
    }

    public function setRouterActive(?bool $routerActive): self
    {
        $this->routerActive = $routerActive;

        return $this;
    }

}
