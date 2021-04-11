<?php

namespace App\Entity;

use App\Util\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @Entity
 */
class Holiday
{
    /**
     * @Id
     * @Column(type="string", length=40)
     */
    private $id;

    /**
     * @Column(type="date")
     */
    private $beginAt;

    /**
     * @Column(type="date")
     */
    private $endAt;

    public function __construct()
    {
        $this->id = Uuid::generateV4();
        $this->beginAt = new \DateTime('next saturday');
        $this->endAt = new \DateTime('next sunday');
    }

    public function getId()
    {
        return $this->id;
    }

    public function match(\DateTime $day)
    {
        $day = $day->setTime(12, 0, 0);
        $begin = $this->beginAt;
        $end = $this->endAt->setTime(23, 59, 59);

        return $day >= $begin && $day <= $end;
    }

    public function getBeginAt()
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTime $beginAt)
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt()
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @Assert\Callback()
     */
    public function assertDateInterval(ExecutionContextInterface $context)
    {
        if ($this->beginAt > $this->endAt) {
            $context->addViolation('La date de début et la date de fin ne sont pas dans le bon ordre');
        }
    }
}
