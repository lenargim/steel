<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="sale_order")
 * @ORM\Entity
 */
class Order
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     * )
     * @Assert\Regex(
     *     pattern= "/^[\w\s-]*$/ui",
     *     message= "Имя введено неправильно. Пожалуйста, используйте только буквы, пробел или тире"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;
    /**
     * @Assert\Regex(
     *     pattern= "/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/",
     *     message= "Номер телефона указан с ошибкой, пример правильного номера: +7-9XX-XX-XXXXX"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message= "Адрес почты указан неверно"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @var array
     * @ORM\Column(type="json_array" , nullable=true)
     */
    protected $products;

    /**
     * 1 -  новый заказ
     * 2 - в обработке
     * 3 - выполненый
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    protected $status;


    /**
     * @Assert\IsTrue(
     *     message= "Вы должны дать согласие на обработку данных"
     * )
     */
    protected $policy;


    public function __construct()
    {
        $this->status = 1;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return array_reduce($this->getProducts(), function ($summ, $item) {
            return $summ + $item['quantity'] * $item['price'];
        }, 0);
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

    public function getMailVariables()
    {
        return [
            'name' => $this->getName(), // Имя клиента
            'text' => $this->getText(), // Текст сообщения клиента
            'phone' => $this->getPhone(), // Телефон клиента
            'email' => $this->getEmail(), // Email клиента
            'address' => $this->getAddress(), // Адрес клиента
            'orderid' => $this->getId(), // Номер заказа
            'table' => '', // Таблица заказа
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() . '-' . $this->getPhone();
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts($products): self
    {
        $this->products = $products;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }



}
