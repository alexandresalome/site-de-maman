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
     * @Route("/menu/{group}/{slug}", name="category_show")
     */
    public function __invoke(string $group, string $slug): Response
    {
        try {
            $category = $this->getDoctrine()->getRepository(Category::class)->findOneByGroupAndSlug($group, $slug);
        } catch (\InvalidArgumentException $e) {
            throw $this->createNotFoundException($e->getMessage(), $e);
        }
        return $this->render('category/show.html.twig', array(
            'category' => $category
        ));
    }
}
