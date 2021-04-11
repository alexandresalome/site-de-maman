<?php

namespace App\Entity;

use App\Cart\Cart;
use App\Util\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Entity(repositoryClass="OrderRepository")
 * @Table(name="`order`")
 */
class Order
{
    /**
     * @Id
     * @Column(type="string", length=40)
     */
    private $id;

    /**
     * @Column(type="json_array", name="`order`")
     */
    private $order;

    /**
     * @Column(type="string", length=128)
     *
     * @NotBlank
     * @Length(max=128)
     */
    private $fullname;

    /**
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Column(type="string", length=128)
     *
     * @NotBlank
     * @Length(max=128)
     */
    private $phone;

    /**
     * @Column(type="string", length=128)
     *
     * @NotBlank
     * @Email
     * @Length(max=128)
     */
    private $email;


    /**
     * @Column(type="string", length=128)
     *
     * @NotBlank
     * @Length(max=128)
     */
    private $date;

    /**
     * @Column(type="text", nullable=true)
     */
    private $observations;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function loadFromCart(Cart $cart)
    {
        $this->order = $cart->toArray();
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(array $order)
    {
        $this->order = $order;

        return $this;
    }

    public function getFullname()
    {
        return $this->fullname;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getObservations()
    {
        return $this->observations;
    }

    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

}
