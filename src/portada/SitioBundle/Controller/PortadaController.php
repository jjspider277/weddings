<?php

namespace portada\SitioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PortadaController extends Controller
{
    public function portadaAction()
    {
        return $this->render('SitioBundle:Portada:portada.html.twig');
    }

    public function busquedaAction()
    {
        return $this->render('SitioBundle:Portada:busqueda.html.twig');
    }
}
