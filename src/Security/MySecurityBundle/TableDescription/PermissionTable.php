<?php

namespace Security\MySecurityBundle\TableDescription;


use backend\ComunBundle\tableDescription\architecture\ColumnaGrid;
use backend\ComunBundle\tableDescription\architecture\GridColumn;
use backend\ComunBundle\tableDescription\architecture\TableModel;
use backend\ComunBundle\Util\ResultType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAware;

class PermissionTable extends TableModel
{
    function __construct() {
        parent::__construct();
        $this->entity = 'Security\MySecurityBundle\Entity\Permission';
        $this->name="Permiso";
    }
    public function defineColumns() {
        $this->columns[] = new GridColumn("Denominación", '30%','denominacion');
        $this->columns[] = new GridColumn("Permiso", '30%','permiso');
    }
    public function constructData()
    {
        $this->datos= $this->getRepo()->filterObjects($this->getTableFiltersByRquest());

        $result = array();
        foreach ($this->datos as $row) {
            $tmpArray=array();
            $tmpArray[] = $row->getDenominacion();
            $tmpArray[] = $row->getPermiso();
            $result[]=$tmpArray;
        }
        return $result;

    }
}