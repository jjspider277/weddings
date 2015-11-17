<?php

namespace portada\SitioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SitioBundle:Default:portada.html.twig', array('name' => $name));
    }
}
