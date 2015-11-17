<?php

namespace Security\MySecurityBundle\Controller;

use backend\ComunBundle\Controller\BaseController;
use backend\ComunBundle\Controller\MyCRUDController;
use backend\ComunBundle\Controller\TreeCRUDController;
use backend\ComunBundle\tableDescription\architecture\RutasGrid;
use backend\ComunBundle\tableDescription\architecture\RutasTree;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PermissionController extends TreeCRUDController
{
    protected $type = 'Security\MySecurityBundle\Form\PermissionType';
    /**
     * @var string Servicio del table model
     */
    protected $treeModelService = 'security.permission.tm';
    /**
     * Metodo Obligatorio de sobreescribir
     * @return RutasGrid
     */
    protected function defineRutas()
    {
        $rutas =new RutasTree();
        $rutas->setNew('security_crud_permission_new');
        $rutas->setDelete('security_crud_permission_delete');
        $rutas->setDetails('security_crud_permission_details');
        $rutas->setEdit('security_crud_permission_edit');
        $rutas->setList('security_crud_permission_list');
        $rutas->setMove('security_crud_permission_move');
        return $rutas;
    }
}
