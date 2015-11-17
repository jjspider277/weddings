<?php

namespace backend\ComunBundle\Controller;

use backend\ComunBundle\tableDescription\architecture\RowActions;
use backend\ComunBundle\tableDescription\architecture\RutasGrid;
use backend\ComunBundle\tableDescription\architecture\TableModel;
use backend\ComunBundle\Util\ArrayResponse;
use backend\ComunBundle\Util\FechaUtil;
use backend\Planeacion\AdminBundle\Enums\EDia;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use backend\ComunBundle\Util\UtilRepository2;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

class MyCRUDController extends BaseController
{
    protected $type = null;
    protected $editType = null;
    protected $view = '@Comun/architecture/components/CRUD/generic_crud.html.twig';
    protected $tableModelService = null;
    protected $createView='@Comun/architecture/components/CRUD/new_content.html.twig';
    protected $editView='@Comun/architecture/components/CRUD/edit_content.html.twig';
    protected $detailsView='@Comun/architecture/components/CRUD/details_content.html.twig';
    protected $textProperty = 'nombre';

    /**
     * @return Response
     */
    public function mainAction()
    {
//        $horas = $this->getRepo('PlaneacionAdminBundle:Hora')->findAll();
//        $dias = $this->getRepo('PlaneacionAdminBundle:Dia')->findAll();
//        foreach($horas as $hora)
//            foreach($dias as $dia){
//                if($hora->getNombre() != '17:50' && $dia->getId() != EDia::Sabado && $dia->getId() != EDia::Domingo)
//                    $hora->addDia($dia);
//                if($hora->getNombre() <= '17:50' && $dia->getId() == EDia::Sabado)
//                    $hora->addDia($dia);
//                $this->getEm()->persist($hora);
//            }
//        $this->getEm()->flush();
//        die;
        return $this->renderMain();

    }
    /**
     * @return Response
     */
    public function renderMain()
    {
        $model = $this->get($this->tableModelService);
        $entity = $model->getEntity();
//        $obj = new $entity();
//        $form = $this->createForm(new $this->type(),$obj );
        return $this->render($this->view,array('model'=>$model,'rutas'=> $model->defineRutas()));
    }
    public function getMainViewHtml($form=null)
    {
        $model = $this->get($this->tableModelService);
        if(!is_null($form))
            $view =$this->renderView($this->view,array('model'=>$model,'rutas'=> $model->defineRutas(),'form'=>$form->createView()));
        else
            $view =$this->renderView($this->view,array('model'=>$model,'rutas'=> $model->defineRutas()));

        return $view;
    }
    public function getCheckeds()
    {
        return array();
    }
    /**
     * @return Response
     */
    public function listAjaxAction()
    {
        $model =$this->get($this->tableModelService);
        if($this->getParameter('firstPetition'))
            $model->setCheckeds($this->getCheckeds());
        return new JsonResponse($this->getGrid($model));
    }
    public function getGrid($model)
    {
        $data = $model->getTransformedData();
        return $data;
    }


    /**
     * @return array
     */
    public function deleteAction()
    {
        $id = $this->getParameter('selected');
        try {
            $result = array();
            $entity = $this->get($this->tableModelService)->getEntity();
            if ($id != null) {
                if (!is_array($id))
                    $id = array($id);
                if (count($id) > 0) {
                    $q = UtilRepository2::getEntityManager()->createQuery("delete from $entity t where t.id in (:ids)")->setParameter('ids', $id);
                    $q->execute();
                    $result["success"] = true; // pass custom message(useful for getting status of group actions)
                    if (count($id) == 1)
                        $result["sMessage"] = "Elemento eliminado satisfactoriamente.";
                    else
                        $result["sMessage"] = "Elementos eliminados satisfactoriamente.";
                }
            }
//        return new ArrayResponse($result);
            $data = $this->get($this->tableModelService)->getTransformedData();
            return new JsonResponse(array_merge($result, $data));
        }
        catch(DBALException $e)
        {
            //23503 FOREIGN Key Violation
//            ldd($e);
            $data = $this->get($this->tableModelService)->getTransformedData();
            return new JsonResponse(array_merge_recursive(array('success'=>false,'sMessage'=>'No se puede eliminar un elemento referenciado.','code'=>$e->getMessage()),$data));
        }
    }
    /**
     * @return Response
     */
    public function newAction()
    {
        $tableModel = $this->get($this->tableModelService);
        $entity = $tableModel->getEntity();
        $obj=null;
        if($entity)
            $obj = new $entity();
        return $this->creadeUpdate($obj,$this->createView);
    }
    /**
     * @return Response
     */
    public function detailsAction()
    {
        $tableModel = $this->get($this->tableModelService);
        $entity = $this->getEm()->getRepository($tableModel->getEntity())->find($this->getParameter('id'));
        $fields = UtilRepository2::getFields($this->getEm(),$tableModel->getEntity());
        $r = array();
        foreach($fields as $field) {
            if(!is_array($entity->{"get" . ucfirst($field)}()))
                $r[] = array('key' => $field, 'value' => $entity->{"get" . ucfirst($field)}());
        }
        return $this->render($this->detailsView,array('obj'=>$r));
    }
    /**
     * @return Response
     */
    public function editAction()
    {
        $tableModel = $this->get($this->tableModelService);
        $entity = $tableModel->getEntity();
        $id = $this->getParameter('id');
        if($id) {
            $obj = $this->getEm()->find($entity, $id);
            return $this->creadeUpdate($obj, $this->editView, "Elemento modificado satisfactoriamente.");
        }
        else
            return new JsonResponse(array("success" => false, "sStatus" => "ERROR", "sMessage" => "Elemento inexistente."));
    }
    protected function creadeUpdate($obj,$view,$msg =  "Elemento creado satisfactoriamente.",$extra_parmas=array())
    {
        $form = null;
        if(array_key_exists('_constructor_params',$extra_parmas)) {
            $form = $this->createForm(new $this->type($extra_parmas['_constructor_params']), $obj);
            unset($extra_parmas['_constructor_params']);
        }
        else
            $form = $this->createForm(new $this->type(),$obj );
        if($obj != null && $obj->getId() != null && $this->editType != null)
            $form = $this->createForm(new $this->editType(),$obj );
        $title= '';
        if($this->getParameter('id') != null && $this->textProperty != null) {
            $method = "get".ucfirst($this->textProperty);
            $title = $obj->$method();
        }

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
//            ldd($obj->getSede());
//            ldd($this->getRequest()->get('stj_basebundle_distritotype'));
//            ldd($form->isValid());
            if($form->isValid()) {
                $em = $this->getEm();
                $em->persist($obj);
                $em->flush();
                if($this->get($this->tableModelService)->isModal())
                    return new JsonResponse(array("success" => true, "sStatus" => "OK", "sMessage" =>$msg));
                else{
                    $html = $this->getMainViewHtml();
                    return new JsonResponse(array("success" => true, "sStatus" => "OK","html"=>$html));
                }
            }
            if($this->get($this->tableModelService)->isModal())
                return new JsonResponse(
                    array('form'=>
                            $this->renderView($view,  array('form' => $form->createview(),'title'=>$title))
                         )
                );
            else {
                return $this->render($this->createView, array_merge(array('form' => $form->createview(), 'model' => $this->get($this->tableModelService)), $extra_parmas));
            }
        }
        if($this->get($this->tableModelService)->isModal())
            return new JsonResponse(
                array('form'=>
                             $this->renderView($view,  array('form' => $form->createview())),
                     'title'=>$title
                     )
            );
        else
            return $this->render($this->createView,  array_merge(array('form' => $form->createview(),'model'=>$this->get($this->tableModelService)),$extra_parmas));
    }

}
