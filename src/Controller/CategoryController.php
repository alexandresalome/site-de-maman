<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/menu/{slug}", name="category_show")
     * @ParamConverter("slug")
     */
    public function __invoke(Category $category): Response
    {
        return $this->render('category/show.html.twig', array(
            'category' => $category
        ));
    }
}
