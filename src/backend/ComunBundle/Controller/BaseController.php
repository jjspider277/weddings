<?php

namespace backend\ComunBundle\Controller;

use backend\ComunBundle\Gestores\BaseGestor;
use backend\ComunBundle\Util\ResultType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use backend\ComunBundle\Util\UtilRepository2;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class BaseController extends Controller
{
    protected $gtr;

    /**
     * @param $entity
     * @param array $filter
     * @param bool $empty Esto es para indicar si va a devolver vacio en caso de que no se filtre por nada
     * @return JsonResponse
     */
    public function jsonRead($entity,$filter=array(),$empty=false)
    {
        $repo = $this->getRepo($entity);
        $alias = UtilRepository2::createAlias($entity);
        $query = $this->getParameter('query');

        $qb = $repo->getQB()->select("$alias.id", "$alias.denominacion");


        $param = $this->getParameter('param');
        $index = $this->getParameter('index');
        $value = $this->getParameter($param)[$index];

//        ld($this->getParameter('filter'));
//        ld($index);
//        ld($param);
//        ldd($value);
        if ($value != null || $empty == false) {
//            $filter = array();
            $name = $this->getParameter('filter');
            if (strpos($name, ".") != false) {
//                ldd('paco');
                $join = explode(".", $name)[0];
                if ($join != $alias)
                    $qb->join($alias . "." . $join, $join);
            }
            if (is_array($value) && count($value) > 0) {
                $value = array('in', $value);
                $filter[$name] = $value;
            }
            if (!is_array($value))
                $filter[$name] = $value;
//            ldd($filter);
            /**
             * Query debe ser una consulta que devuelve un QueryBuilder
             */
            if ($query)
                $qb = $repo->{$query}() ;

            $r = $repo->filterQB($qb, $filter, ResultType::ArrayType);
            $result = array();
            foreach ($r as $row)
                $result[$row['id']] = $row['denominacion'];
            return new JsonResponse($result);
        }
        return new JsonResponse(array());

    }

    public function getRepo($entity)
    {
        return $this->getEm()->getRepository($entity);
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    public function getParameter($key, $default = null)
    {
        $action = $this->container->get('request')->get($key);
        if (is_array($action))
            $action = array_unique($action);
        if (!$action)
            return $default;
        return $action;
    }

    public function saveKeyToSession($key, $value)
    {
        $this->getRequest()->getSession()->set($key, $value);
    }

    public function existKeyInSession($key)
    {
        return $this->getRequest()->getSession()->has($key);
    }

    public function removeKeyFromSession($key){
        return $this->getRequest()->getSession()->remove($key);
    }
    public function loadKeyFromSession($key)
    {
        return $this->getRequest()->getSession()->get($key);
    }

    public function saveFlash($key, $value)
    {
        $this->get('session')->getFlashBag()->add($key, $value);
    }

    public function getFlash($key)
    {
        return $this->get('session')->getFlashBag()->get($key);
    }

    /**
     * @return BaseGestor
     */
    public function getGestor()
    {
        return $this->get('comunes.basegtr');
    }

    public function setGestor($gestor)
    {
        $this->gtr = $gestor;
    }

    public function DocumentoGenerarHtml($plantilla, $ds)
    {
        $html = $this->get('comun.documentosgtr')->GenerarHtmlTwig($plantilla, $ds);
        return $html;
    }

    /**
     *  Genera la respuesta (Response), con el pdf incluído y listo para ser
     *  descargado en la parte del cliente
     * @param type $html
     * @param type $nombrePdf
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function DocumentoGenerarPdf_Html($html, $nombrePdf)
    {
        $pdf = $this->get('comun.documentosgtr')->GenerarPdf($html);
        return new Response(
            $pdf, 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombrePdf . '.pdf"'
            )
        );
    }

}
