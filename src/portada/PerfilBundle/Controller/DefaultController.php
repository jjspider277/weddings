<?php

namespace portada\PerfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PerfilBundle:Default:index.html.twig', array());
    }
}
