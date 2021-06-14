<?php
namespace  SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SiteBundle\Entity\Traits\Gallery;
use SiteBundle\Entity\Traits\ImageUpload;

/**
 * @ORM\Table(name="page_gallery")
 * @ORM\Entity
 */
class PageGallery
{
    use ImageUpload;
    use Gallery;

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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $button;

    protected $upload_dir = "page";



    /**
     * Set page
     *
     * @param \SiteBundle\Entity\Pages $page
     *
     * @return PageGallery
     */
    public function setPage(\SiteBundle\Entity\Pages $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \SiteBundle\Entity\Pages
     */
    public function getPage()
    {
        return $this->page;
    }



    /**
     * Set pageId
     *
     * @param integer $pageId
     *
     * @return PageGallery
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getButton(): ?string
    {
        return $this->button;
    }

    public function setButton(?string $button): self
    {
        $this->button = $button;

        return $this;
    }
}
