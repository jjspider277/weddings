<?php

namespace Portada\SitioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusquedaController extends Controller
{



    public function busquedaAction()
    {

        return $this->render('SitioBundle:Portada:busqueda.html.twig');
    }
}
