<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Holiday;
use AppBundle\Form\Type\HolidayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminHolidayController extends Controller
{
    /**
     * @Route(path="/admin/holiday", name="admin_holiday_index")
     */
    public function homepageAction()
    {
        $holidays = $this->getDoctrine()->getRepository(Holiday::class)->findAll();

        return $this->render('admin_holiday/index.html.twig', array(
            'holidays' => $holidays,
            'available_times' => $this->get('planning')->getAvailableTimes(),
            'form_holiday' => $this->createForm(HolidayType::class)->createView()
        ));
    }

    /**
     * @Route(path="/admin/holiday/create", name="admin_holiday_create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(HolidayType::class);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('admin_holiday_index');
        }

        $holidays = $this->getDoctrine()->getRepository(Holiday::class)->findAll();

        return $this->render('admin_holiday/index.html.twig', array(
            'holidays' => $holidays,
            'available_times' => $this->get('planning')->getAvailableTimes(),
            'form_holiday' => $form->createView()
        ));
    }

    /**
     * @Route(path="/admin/holiday/delete/{id}", name="admin_holiday_delete")
     * @ParamConverter
     */
    public function deleteAction(Holiday $holiday)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($holiday);
        $em->flush();

        return $this->redirectToRoute('admin_holiday_index');
    }
}
