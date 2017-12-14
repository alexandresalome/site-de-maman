<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Meal;
use AppBundle\Form\Type\CategoryType;
use AppBundle\Form\Type\MealType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AdminMenuController extends Controller
{
    /**
     * @Route(path="/admin/menu", name="admin_menu_index")
     */
    public function indexAction()
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
     * @ParamConverter
     */
    public function categoryEditAction(Request $request, Category $category)
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
     * @ParamConverter
     */
    public function categoryCreateAction(Request $request)
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
                $category->getName()
            ));

            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render('admin_menu/category_create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(path="/admin/menu/category/{id}/delete", name="admin_menu_category_delete")
     * @ParamConverter
     */
    public function categoryDeleteAction(Request $request, Category $category)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La catégorie '.$category->getName().' a bien été supprimée.');

            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render('admin_menu/category_delete.html.twig', array(
            'category' => $category
        ));
    }

    /**
     * @Route(path="/admin/menu/meal/{id}", name="admin_menu_meal_edit")
     * @ParamConverter
     */
    public function mealEditAction(Request $request, Meal $meal)
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
     * @ParamConverter
     */
    public function mealCreateAction(Request $request, Category $category)
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
     * @ParamConverter
     */
    public function mealDeleteAction(Request $request, Meal $meal)
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
