<?php

namespace App\Entity;

use App\Price\Price;
use App\Util\Uuid;
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
    private string $id;

    /**
     * @ManyToOne(targetEntity="Category", inversedBy="meals")
     * @JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Category $category = null;

    /**
     * @Column(type="string", length=128)
     * @NotBlank
     */
    private ?string $name = null;

    /**
     * @Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @Column(type="string", length=10)
     * @Range(min=1, minMessage="Le prix n'est pas correct, maman")
     */
    private string $price = '0';

    /**
     * @Column(type="integer")
     */
    private int $position = 1;

    /**
     * @Column(type="boolean")
     */
    private bool $active = true;

    /**
     * @Column(type="boolean")
     */
    private bool $portuguese = false;

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

    public function isPortuguese()
    {
        return $this->portuguese;
    }

    public function setPortuguese($portuguese = true)
    {
        $this->portuguese = $portuguese;

        return $this;
    }
}
