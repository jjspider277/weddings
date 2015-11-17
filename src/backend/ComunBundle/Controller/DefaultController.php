<?php

namespace backend\ComunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use backend\ComunBundle\Util\UtilRepository2;

class DefaultController extends Controller
{
    public function readAction()
    {
//        ldd($this->getUser());
        return $this->render('@Comun/Default/portada.html.twig');
    }
}
