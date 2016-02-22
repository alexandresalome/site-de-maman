<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminMenuController extends Controller
{
    /**
     * @Route(path="/admin/menu", name="admin_menu_index")
     */
    public function indexAction()
    {
        return $this->render('admin_menu/index.html.twig');
    }
}
