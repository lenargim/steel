<?php
namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="docs")
 * @ORM\Entity *
 * @Vich\Uploadable
 */
class PageDocs
{

    protected $upload_dir = "product";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;


    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $doc;


    /**
     * @Vich\UploadableField(mapping="docs", fileNameProperty="doc.name", size="doc.size", mimeType="doc.mimeType", originalName="doc.originalName", dimensions="doc.dimensions"))
     * @var File
     */
    private $docFile;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $typeView;


    public function __construct()
    {
        $this->doc = new \Vich\UploaderBundle\Entity\File();
    }

    public function __toString()
    {
        return $this->getTitle()?:'';
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $doc
     */
    public function setDocFile(?File $doc = null)
    {
        $this->docFile = $doc;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($doc) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getDocFile(): ?File
    {
        return $this->docFile;
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
     * @return $this
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
     * Set pageId
     *
     * @param integer $pageId
     *
     * @return PageDocs
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

    /**
     * Set page
     *
     * @param \SiteBundle\Entity\Pages $page
     *
     * @return PageDocs
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
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return PageDocs
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getDoc(): ?EmbeddedFile
    {
        return $this->doc;
    }

    public function setDoc(EmbeddedFile $doc): self
    {
        $this->doc = $doc;

        return $this;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return PageDocs
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

    public function getTypeView(): ?string
    {
        return $this->typeView;
    }

    public function setTypeView(?string $typeView): self
    {
        $this->typeView = $typeView;

        return $this;
    }
}
