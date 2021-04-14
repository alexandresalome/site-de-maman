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
        $categoryCountFr = $this->getDoctrine()->getRepository(Category::class)->countAll('fr');
        $categoryCountPt = $this->getDoctrine()->getRepository(Category::class)->countAll('pt');
        $mealCountFr = $this->getDoctrine()->getRepository(Meal::class)->countAll('fr');
        $mealCountPt = $this->getDoctrine()->getRepository(Meal::class)->countAll('pt');

        $firstAvailableTime = $this->get(Planning::class)->getFirstAvailableTime();

        return $this->render('admin/index.html.twig', array(
            'orders' => $orders,
            'category_count_fr' => $categoryCountFr,
            'category_count_pt' => $categoryCountPt,
            'meal_count_fr' => $mealCountFr,
            'meal_count_pt' => $mealCountPt,
            'first_available_time' => $firstAvailableTime,
        ));
    }
}
