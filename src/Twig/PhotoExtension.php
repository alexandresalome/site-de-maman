<?php

namespace App\Twig;

use App\Entity\Meal;
use App\Service\PhotoService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PhotoExtension extends AbstractExtension
{
    private PhotoService $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    public function getMealPhoto(Meal $meal, $size)
    {
        return $this->photoService->get($meal, $size);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_meal_photo', [$this, 'getMealPhoto']),
        ];
    }
}
