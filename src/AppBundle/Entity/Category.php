<?php

namespace AppBundle\Entity;

use AppBundle\Util\Uuid;
use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @Entity(repositoryClass="CategoryRepository")
 */
class Category
{
    /**
     * @Id
     * @Column(type="string", length=40)
     */
    private $id;

    /**
     * @OneToMany(targetEntity="Meal", mappedBy="category")
     */
    private $meals;

    /**
     * @Column(type="string", length=128)
     */
    private $name;

    /**
     * @Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @Column(type="integer")
     */
    private $position = 1;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
        $this->meals = new ArrayCollection();
    }

    public function getMeals()
    {
        return $this->meals;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setName($name)
    {
        $this->name = $name;
        $this->slug = Transliterator::urlize($name);

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
