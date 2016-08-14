<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route(path="/admin", name="admin_index")
     */
    public function homepageAction()
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findLatest(5);
        $categoryCount = $this->getDoctrine()->getRepository(Category::class)->countAll();
        $mealCount = $this->getDoctrine()->getRepository(Meal::class)->countAll();

        $firstAvailableTime = $this->get('planning')->getFirstAvailableTime();

        return $this->render('admin/index.html.twig', array(
            'orders' => $orders,
            'category_count' => $categoryCount,
            'meal_count' => $mealCount,
            'first_available_time' => $firstAvailableTime,
        ));
    }
}
