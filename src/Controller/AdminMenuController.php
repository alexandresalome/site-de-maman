<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Meal;
use App\Form\Type\CategoryType;
use App\Form\Type\MealType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMenuController extends AbstractController
{
    /**
     * @Route(path="/admin/menu", name="admin_menu_index")
     */
    public function indexAction(): Response
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findOrderedByPosition()
        ;

        return $this->render('admin_menu/index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * @Route(path="/admin/menu/category/{id}", name="admin_menu_category_edit")
     * @ParamConverter("id")
     */
    public function categoryEditAction(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf(
                'Catégorie "%s" mise à jour.',
                $category->getName()
            ));

            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render('admin_menu/category_edit.html.twig', array(
            'category' => $category,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(path="/admin/menu/create-category", name="admin_menu_category_create")
     */
    public function categoryCreateAction(Request $request): Response
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', sprintf(
                'Catégorie "%s" créée.',
                $category->getName(),
            ));

            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render('admin_menu/category_create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(path="/admin/menu/category/{id}/delete", name="admin_menu_category_delete")
     * @ParamConverter("id")
     */
    public function categoryDeleteAction(Request $request, Category $category): Response
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', sprintf(
                "La catégorie %s a bien été supprimée.",
                $category->getName(),
            ));

            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render('admin_menu/category_delete.html.twig', array(
            'category' => $category
        ));
    }

    /**
     * @Route(path="/admin/menu/meal/{id}", name="admin_menu_meal_edit")
     * @ParamConverter("id")
     */
    public function mealEditAction(Request $request, Meal $meal): Response
    {
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf(
                'Plat "%s" mis à jour.',
                $meal->getName()
            ));

            return $this->redirectToRoute('admin_menu_category_edit', array('id' => $meal->getCategory()->getId()));
        }

        return $this->render('admin_menu/meal_edit.html.twig', array(
            'meal' => $meal,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(path="/admin/menu/create-meal/{id}", name="admin_menu_meal_create")
     * @ParamConverter("id")
     */
    public function mealCreateAction(Request $request, Category $category): Response
    {
        $form = $this->createForm(MealType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $meal = $form->getData();

            $meal->setCategory($category);

            $em->persist($meal);
            $em->flush();

            $this->addFlash('success', sprintf(
                'Plat "%s" créé.',
                $meal->getName()
            ));

            return $this->redirectToRoute('admin_menu_meal_edit', array('id' => $meal->getId()));
        }

        return $this->render('admin_menu/meal_create.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }

    /**
     * @Route(path="/admin/menu/meal/{id}/delete", name="admin_menu_meal_delete")
     * @ParamConverter("id")
     */
    public function mealDeleteAction(Request $request, Meal $meal): Response
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meal);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le plat '.$meal->getName().' a bien été supprimé.');

            return $this->redirectToRoute('admin_menu_category_edit', array('id' => $meal->getCategory()->getId()));
        }

        return $this->render('admin_menu/meal_delete.html.twig', array(
            'meal' => $meal
        ));
    }
}
