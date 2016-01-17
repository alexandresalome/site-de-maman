<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * @Route("/menu/{slug}", name="category_show")
     * @ParamConverter
     */
    public function showAction(Category $category)
    {
        return $this->render('category/show.html.twig', array(
            'category' => $category
        ));
    }
}
