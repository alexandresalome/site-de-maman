<?php

namespace App\Entity;

use App\Util\Uuid;
use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CategoryRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"group_name", "slug"})})
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    private string $id;

    /**
     * @ORM\OneToMany(targetEntity="Meal", mappedBy="category")
     * @var Meal[]|ArrayCollection
     */
    private mixed $meals;

    /**
     * @ORM\Column(name="group_name", type="string", length=16)
     */
    private ?string $group = null;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $position = 1;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
        $this->meals = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Meal[]
     */
    public function getMeals($activeOnly = true): array
    {
        $meals = array();

        foreach ($this->meals as $meal) {
            if (!$activeOnly || $meal->isActive()) {
                $meals[] = $meal;
            }
        }

        usort($meals, function ($left, $right) {
            return $left->getPosition() <=> $right->getPosition();
        });

        return $meals;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        $this->slug = Transliterator::urlize($name);

        return $this;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(string $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition($position): static
    {
        $this->position = $position;

        return $this;
    }
}
