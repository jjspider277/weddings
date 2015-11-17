<?php

namespace backend\AdministracionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdministracionBundle:Default:index.html.twig', array());
    }
}
