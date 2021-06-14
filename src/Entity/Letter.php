<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="letter")
 * @ORM\Entity
 */
class Letter
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $typeform;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $adding1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $adding2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $adding3;

    /**
     * 1 -  новый заказ
     * 2 - в обработке
     * 3 - выполненый
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    protected $status;

    /**
     * @param $obj
     * @return Letter
     */
    public static function getInstanceByMail($obj)
    {
        $instance = new Letter();
        $instance->setName($obj->getName());
        $instance->setPhone($obj->getPhone());
        if (method_exists($obj, 'getEmail')) {
            $instance->setEmail($obj->getEmail());
        }
        if (method_exists($obj, 'getText')) {
            $instance->setText($obj->getText());
        }
        if (method_exists($obj, 'getUrl')) {
            $instance->setAdding1($obj->getUrl());
        }
        if (method_exists($obj, 'getOrderObj')) {
            $instance->setAdding2($obj->getOrderObj());
        }
        if (method_exists($obj, 'getCompany')) {
            $instance->setAdding3($obj->getCompany());
        }
        $instance->setTypeform($obj->getTypeform());
        return $instance;
    }

    public function __construct()
    {
        $this->status = 1;
    }

    public function __toString()
    {
        return $this->getTypeform() . '-' . $this->getName() . '-' . $this->getPhone();
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }


    /**
     * @return mixed
     */
    public function getTypeform()
    {
        return $this->typeform;
    }

    /**
     * @param mixed $typeform
     */
    public function setTypeform($typeform)
    {
        $this->typeform = $typeform;
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
     * Set status
     *
     * @param integer $status
     *
     * @return Letter
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Letter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Letter
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getAdding1()
    {
        return $this->adding1;
    }

    /**
     * @param mixed $adding1
     */
    public function setAdding1($adding1): void
    {
        $this->adding1 = $adding1;
    }

    /**
     * @return mixed
     */
    public function getAdding2()
    {
        return $this->adding2;
    }

    /**
     * @param mixed $adding2
     */
    public function setAdding2($adding2): void
    {
        $this->adding2 = $adding2;
    }

    public function getAdding3(): ?string
    {
        return $this->adding3;
    }

    public function setAdding3(?string $adding3): self
    {
        $this->adding3 = $adding3;

        return $this;
    }


}
