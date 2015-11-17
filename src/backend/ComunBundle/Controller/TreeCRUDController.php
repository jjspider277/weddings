<?php

namespace backend\ComunBundle\Controller;

use backend\ComunBundle\tableDescription\architecture\RowActions;
use backend\ComunBundle\tableDescription\architecture\RutasGrid;
use backend\ComunBundle\tableDescription\architecture\RutasTree;
use backend\ComunBundle\tableDescription\architecture\TableModel;
use backend\ComunBundle\Util\ArrayResponse;
use backend\ComunBundle\Util\FechaUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use backend\ComunBundle\Util\UtilRepository2;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

class TreeCRUDController extends BaseController
{
    protected $type = null;
    protected $view = '@Comun/architecture/components/CRUD/generic_tree_crud.html.twig';
    protected $treeModelService = null;
    protected $createView='@Comun/architecture/components/CRUD/new_content.html.twig';
    protected $editView='@Comun/architecture/components/CRUD/edit_content.html.twig';
    protected $detailsView='@Comun/architecture/components/CRUD/details_content.html.twig';

    /**
     * @Route("/crud/tree/main",name="comun_tree_crud_main")
     * @return Response
     */
    public function mainAction()
    {
        $rutas = $this->defineRutas();
        return $this->renderMain($rutas);

    }
    /**
     * @Route("/crud/tree/move",name="comun_tree_crud_move")
     * @return Response
     */
    public function moveNodeAction()
    {

        $nodeId = $this->getParameter("node");
        if(!is_numeric($nodeId)) {
            $array = explode("_", $nodeId);
            $nodeId = $array[count($array)-1];
        }

        $parentId = $this->getParameter("parent");
        if(!is_numeric($parentId )) {
            $array = explode("_", $parentId);
            $parentId = $array[count($array)-1];
        }

        $em = $this->getEm();
        $repo = $this->getRepo($this->get($this->treeModelService)->getEntity());

        $parent=null;
        if(is_numeric($nodeId)) {
            $node = $repo->find($nodeId);
            if(!is_numeric($parentId))
                $parentId = -1;
            $parent = $repo->find($parentId);
            $node->setPadre($parent);
            $em->persist($node);
            $em->flush();
            return new JsonResponse(array("success"=>true,'paco'=>false));
        }
        return new JsonResponse(array("success"=>false));
    }
    /**
     * @return Response
     */
    public function renderMain($rutas)
    {
        $model = $this->get($this->treeModelService);
        return $this->render($this->view,array('model'=>$model,'rutas'=> $rutas));
    }
    /**
     * @Route("/crud/tree/list",name="comun_tree_crud_list")
     * @return Response
     */
    public function listAction()
    {
        $records = $this->getTree($this->treeModelService);
        return new JsonResponse($records);
    }
    public function getTree($modelService)
    {
        $data = $this->get($modelService)->getTransformedData();
        return $data;
    }

    /**
     * @return RutasGrid
     */
     protected function defineRutas(){
         $rutas =new RutasTree();
         $rutas->setNew('security_tree_crud_new');
         $rutas->setDelete('security_tree_crud_delete');
         $rutas->setEdit('security_tree_crud_edit');
         $rutas->setList('security_tree_crud_list');
         $rutas->setDetails('security_tree_crud_details');
     }
    /**
     * @Route("/crud/tree/delete",name="comun_tree_crud_delete")
     * @return array
     */
    public function deleteAction()
    {
        $result = array();
        $result['success']=false;
        $entity = $this->get($this->treeModelService)->getEntity();
        $id = $this->getParameter('id');
        if(!is_numeric($id )) {
            $array = explode("_", $id);
            $id = $array[count($array)-1];
        }

        if($id && is_numeric($id)) {
            if(!is_array($id))
                $id=array($id);
            if(count($id)>0) {
                $q = UtilRepository2::getEntityManager()->createQuery("delete from $entity t where t.id in (:ids)")->setParameter('ids', $id);
                $q->execute();
                $result["success"] = true; // pass custom message(useful for getting status of group actions)
                if(count($id) == 1)
                    $result["sMessage"] = "Elemento eliminado satisfactoriamente.";
                else
                    $result["sMessage"] = "Elementos eliminados satisfactoriamente.";
            }
        }
        return new JsonResponse($result);
    }
    /**
     * @Route("/crud/tree/new",name="comun_tree_crud_new")
     * @return Response
     */
    public function newAction()
    {
        $tableModel = $this->get($this->treeModelService);
        $entity = $tableModel->getEntity();
        $obj = new $entity();
        return $this->creadeUpdate($obj,$this->createView);
    }
    /**
     * @Route("/crud/tree/details",name="comun_tree_crud_details")
     * @return Response
     */
    public function detailsAction()
    {
        $tableModel = $this->get($this->treeModelService);

        $id = $this->getParameter('id');
        if(!is_numeric($id )) {
            $array = explode("_", $id);
            $id = $array[count($array)-1];
        }
        $entity = $this->getEm()->getRepository($tableModel->getEntity())->find($id);
        $fields = UtilRepository2::getFields($this->getEm(),$tableModel->getEntity());
        $r = array();
        foreach($fields as $field) {
            if(!is_array($entity->{"get" . ucfirst($field)}()))
                $r[] = array('key' => $field, 'value' => $entity->{"get" . ucfirst($field)}());
        }
        return $this->render($this->detailsView,array('obj'=>$r));
    }
    /**
     * @Route("/crud/tree/edit",name="comun_tree_crud_edit")
     * @return Response
     */
    public function editAction()
    {
        $tableModel = $this->get($this->treeModelService);
        $entity = $tableModel->getEntity();
        $id = $this->getParameter('id');
        if(!is_numeric($id )) {
            $array = explode("_", $id);
            $id = $array[count($array)-1];
        }
        if($id) {
            $obj = $this->getEm()->find($entity, $id);
            return $this->creadeUpdate($obj, $this->editView, "Elemento modificado satisfactoriamente.");
        }
        else
            return new JsonResponse(array("success" => false, "sMessage" => "Elemento inexistente."));
    }
    protected function creadeUpdate($obj,$view,$msg =  "Elemento creado satisfactoriamente.")
    {
        $form = $this->createForm(new $this->type(),$obj );

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
//            ldd($form->getErrorsAsString());
            if($form->isValid()) {
                $em = $this->getEm();
                if($obj->getPadre() == null) {

                    $parent = $this->getParameter('parent',-1);
                    if(!is_numeric($parent )) {
                        $array = explode("_", $parent);
                        $parent = $array[count($array)-1];
                    }

                    $obj->setPadre($em->find($this->get($this->treeModelService)->getEntity(), $parent));
                }
                $em->persist($obj);
                $em->flush();
                $method = $this->get($this->treeModelService)->getNodeText();
                return new JsonResponse(array("success" => true, "sStatus" => "OK", "sMessage" =>$msg,"text"=>$obj->$method(),"id"=>$obj->getId()));
            }
            return new JsonResponse(
                array('form'=>
                        $this->renderView($view,  array('form' => $form->createview()))
                     )
            );
        }
        elseif($this->getParameter('id') != null)
            return new JsonResponse(
                array('form'=>
                    $this->renderView($view,  array('form' => $form->createview()))
                )
            );
        return $this->render($view, array(
            'form' => $form->createview(),
        ));
    }

}
