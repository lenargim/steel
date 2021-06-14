<?php
namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * Settings
 */
class Redirects
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $redirectfrom;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $redirectto;

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
     * Set redirectfrom
     *
     * @param string $redirectfrom
     *
     * @return Redirects
     */
    public function setRedirectfrom($redirectfrom)
    {
        $this->redirectfrom = $redirectfrom;

        return $this;
    }

    /**
     * Get redirectfrom
     *
     * @return string
     */
    public function getRedirectfrom()
    {
        return $this->redirectfrom;
    }

    /**
     * Set redirectto
     *
     * @param string $redirectto
     *
     * @return Redirects
     */
    public function setRedirectto($redirectto)
    {
        $this->redirectto = $redirectto;

        return $this;
    }

    /**
     * Get redirectto
     *
     * @return string
     */
    public function getRedirectto()
    {
        return $this->redirectto;
    }
}
