<?php
namespace SiteBundle\Entity;

use SiteBundle\Entity\Traits\ImageUpload;
use Doctrine\ORM\Mapping as ORM;
use SiteBundle\Entity\Traits\UniversalFields;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="main_blocks")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\PageBlocksRepository")
 */
class PageBlocks
{
    use ImageUpload;
    use UniversalFields;

    protected $upload_dir = "page";

    /**
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Pages")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $page;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $page_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $type;


    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;



    // upload docs
    /**
     * @Assert\File( maxSize="20M")
     * @ORM\Column(type="array", nullable=true)
     **/
    private $doc;

    public function __toString()
    {
        return $this->getTitle() ?: 'Новый блок';
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PageBlocks
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return PageBlocks
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return PageBlocks
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

    public function getPageId(): ?int
    {
        return $this->page_id;
    }

    public function setPageId(?int $page_id): self
    {
        $this->page_id = $page_id;

        return $this;
    }

    public function getDoc(): ?array
    {
        return $this->doc;
    }

    public function setDoc(?array $doc): self
    {
        $this->doc = $doc;

        return $this;
    }

    public function getPage(): ?Pages
    {
        return $this->page;
    }

    public function setPage(?Pages $page): self
    {
        $this->page = $page;

        return $this;
    }

}
