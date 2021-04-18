<?php

namespace App\Controller;

use App\Service\PhotoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPortageController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
            PhotoService::class,
        ];
    }

    /**
     * @Route(path="/admin/portage", name="admin_portage_index")
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(FileType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $file = $form->getData();
            $this->get(PhotoService::class)->uploadPortage($file);

            return $this->redirectToRoute('admin_portage_index');
        }

        return $this->render('admin_portage/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
