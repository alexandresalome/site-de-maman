<?php

namespace App\Controller;

use App\Entity\Holiday;
use App\Form\Type\HolidayType;
use App\Service\Planning;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHolidayController extends AbstractController
{
    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
            Planning::class,
        ];
    }

    /**
     * @Route(path="/admin/holiday", name="admin_holiday_index")
     */
    public function indexAction(): Response
    {
        $holidays = $this->getDoctrine()->getRepository(Holiday::class)->findAll();

        return $this->render('admin_holiday/index.html.twig', array(
            'holidays' => $holidays,
            'available_times' => $this->get(Planning::class)->getAvailableTimes(),
            'form_holiday' => $this->createForm(HolidayType::class)->createView()
        ));
    }

    /**
     * @Route(path="/admin/holiday/create", name="admin_holiday_create")
     */
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(HolidayType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('admin_holiday_index');
        }

        $holidays = $this->getDoctrine()->getRepository(Holiday::class)->findAll();

        return $this->render('admin_holiday/index.html.twig', array(
            'holidays' => $holidays,
            'available_times' => $this->get(Planning::class)->getAvailableTimes(),
            'form_holiday' => $form->createView()
        ));
    }

    /**
     * @Route(path="/admin/holiday/delete/{id}", name="admin_holiday_delete")
     * @ParamConverter("id")
     */
    public function deleteAction(Holiday $holiday): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($holiday);
        $em->flush();

        return $this->redirectToRoute('admin_holiday_index');
    }
}
