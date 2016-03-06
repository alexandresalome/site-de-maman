<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Meal;
use AppBundle\Service\PhotoService;

class PhotoExtension extends \Twig_Extension
{
    private $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    public function getMealPhoto(Meal $meal, $size)
    {
        return $this->photoService->get($meal, $size);
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_meal_photo', array($this, 'getMealPhoto'))
        );
    }

    public function getName()
    {
        return 'photo';
    }
}
