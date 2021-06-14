<?php
namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\SettingsRepository")
 * @ORM\Table(name="settings")
 * @Vich\Uploadable
 */
class Settings
{
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
    protected $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

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

    public function __construct()
    {
        $this->doc = new \Vich\UploaderBundle\Entity\File();
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

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string|null $title
     *
     * @return Settings
     */
    public function setTitle($title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set alias.
     *
     * @param string|null $alias
     *
     * @return Settings
     */
    public function setAlias($alias = null)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias.
     *
     * @return string|null
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set string.
     *
     * @param string|null $string
     *
     * @return Settings
     */
    public function setString($string = null)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * Get string.
     *
     * @return string|null
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * Set text.
     *
     * @param string|null $text
     *
     * @return Settings
     */
    public function setText($text = null)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Settings
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


}
