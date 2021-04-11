<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Meal;
use App\Entity\Order;
use App\Service\Planning;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
            Planning::class,
        ];
    }

    /**
     * @Route(path="/admin", name="admin_index")
     */
    public function __invoke(): Response
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findLatest(5);
        $categoryCount = $this->getDoctrine()->getRepository(Category::class)->countAll();
        $mealCount = $this->getDoctrine()->getRepository(Meal::class)->countAll();

        $firstAvailableTime = $this->get(Planning::class)->getFirstAvailableTime();

        return $this->render('admin/index.html.twig', array(
            'orders' => $orders,
            'category_count' => $categoryCount,
            'meal_count' => $mealCount,
            'first_available_time' => $firstAvailableTime,
        ));
    }
}
