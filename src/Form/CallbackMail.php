<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

class CallbackMail
{

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     * )
     * @Assert\Regex(
     *     pattern= "/^[\w\s-]*$/ui",
     *     message= "Имя введено неправильно. Пожалуйста, используйте только буквы, пробел или тире"
     * )
     */
    protected $name;
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern= "/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/",
     *     message= "Номер телефона указан с ошибкой, пример правильного номера: +7-9XX-XX-XXXXX"
     * )
     */
    protected $phone;


    /**
     * @Assert\IsTrue(
     *     message= "Вы должны дать согласие на обработку данных"
     * )
     */
    protected $policy;


    /**
     */
    protected $typeform;

    /**
     */
    protected $url;

    /**
     * 1 -  новый заказ
     * 2 - в обработке
     * 3 - выполненый
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    protected $status;

    public function __construct()
    {
        $this->typeform = 'callback';
        $this->status = 1;
    }

    public function __toString()
    {
        return $this->getTypeform() . '-' . $this->getName() . '-' . $this->getPhone();
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
    public function getPolicy()
    {
        return $this->policy;
    }

    /**
     * @param mixed $policy
     */
    public function setPolicy($policy)
    {
        $this->policy = $policy;
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
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }




    /**
     * Set status
     *
     * @param integer $status
     *
     * @return CallbackMail
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
}
