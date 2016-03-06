<?php

namespace AppBundle\Entity;

use AppBundle\Price\Price;
use AppBundle\Util\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @Entity(repositoryClass="MealRepository")
 */
class Meal
{
    /**
     * @Id
     * @Column(type="string", length=40)
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Category", inversedBy="meals")
     * @JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $category;

    /**
     * @Column(type="string", length=128)
     * @NotBlank
     */
    private $name;

    /**
     * @Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Column(type="string", length=10)
     * @Range(min=1, minMessage="Le prix n'est pas correct, maman")
     */
    private $price = '0';

    /**
     * @Column(type="integer")
     */
    private $position = 1;

    /**
     * @Column(type="boolean")
     */
    private $active = true;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
    }

    public function getId()
    {
        return $this->id;
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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

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

    public function getPrice()
    {
        return new Price($this->price);
    }

    public function setPrice(Price $price)
    {
        $this->price = $price->getAmount();

        return $this;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setActive($active = true)
    {
        $this->active = $active;

        return $this;
    }
}
