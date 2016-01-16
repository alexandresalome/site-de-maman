<?php

namespace AppBundle\Entity;

use AppBundle\Util\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity
 */
class Meal
{
    /**
     * @Id
     * @Column(type="string", length=40)
     */
    private $id;

    /**
     * @Id
     * @ManyToOne(targetEntity="Category", nullable=false)
     * @JoinColumn
     */
    private $category;

    /**
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @Column(type="text")
     */
    private $description;

    /**
     * @Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @Column(type="integer")
     */
    private $delay = 1;

    /**
     * @Column(type="integer")
     */
    private $order = 1;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
    }
}
