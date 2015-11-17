<?php

namespace Security\MySecurityBundle\TreeDescription;


use backend\ComunBundle\tableDescription\architecture\ColumnaGrid;
use backend\ComunBundle\tableDescription\architecture\GridColumn;
use backend\ComunBundle\tableDescription\architecture\TableModel;
use backend\ComunBundle\tableDescription\architecture\TreeModel;
use backend\ComunBundle\Util\ResultType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAware;

class PermissionTree extends TreeModel
{
    function __construct() {
        parent::__construct();
        $this->entity = 'Security\MySecurityBundle\Entity\Permission';
        $this->name="Permiso";
        $this->rootNode = "Todos los permisos";
    }
}