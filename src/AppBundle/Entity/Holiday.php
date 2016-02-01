<?php

namespace AppBundle\Entity;

use AppBundle\Util\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

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
}
