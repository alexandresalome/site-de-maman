<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity
 */
class Category
{
    /**
     * @Id
     * @Column(type="string", length=40)
     */
    private $id;

    /**
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @Column(type="integer")
     */
    private $order = 1;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
