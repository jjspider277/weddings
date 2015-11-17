<?php

namespace backend\AdministracionBundle\TableDescription;


use backend\ComunBundle\tableDescription\architecture\ColumnaGrid;
use backend\ComunBundle\tableDescription\architecture\GridColumn;
use backend\ComunBundle\tableDescription\architecture\RutasGrid;
use backend\ComunBundle\tableDescription\architecture\SelectFilterColumn;
use backend\ComunBundle\tableDescription\architecture\TableModel;
use backend\ComunBundle\Util\ResultType;
use backend\ComunBundle\Util\Util;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAware;

class TipoParticipanteTable extends TableModel
{
    function __construct() {
        parent::__construct();
        $this->entity = 'backend\ComunBundle\Entity\TipoParticipante';
        $this->name="Tipo de Participante";
        $this->editTitle="hora";
    }
    public function defineColumns() {
        $nameColumn = new GridColumn("Nombre", '25%','nombre','text');
        $nameColumn->setDefaultOrder(true);

        $this->columns[] =$nameColumn;

    }
    public function defineRutas()
    {
        $rutas =new RutasGrid();

        $rutas->setNew('administracion_crud_tipoparticipante_new');
        $rutas->setDelete('administracion_crud_tipoparticipante_delete');
//        $rutas->setDetails('planeacion_admin_crud_materia_details');
        $rutas->setEdit('administracion_crud_tipoparticipante_edit');
        $rutas->setList('administracion_crud_tipoparticipante_listAjax');
     //   $rutas->setActivar('admin_crud_tipoparticipante_activar');
     //   $rutas->setDesactivar('admin_crud_tipoparticipante_desactivar');
        return $rutas;
    }
    public function constructData()
    {
        $order = $this->getTableSortByRequest();
        $filters = $this->getTableFiltersByRquest();
//        ldd($order);
        /**
         * @var $qb QueryBuilder
         */
        $qb =  $this->getRepo()->getQB();

        $this->datos = $this->getRepo()->filterQB($qb,$filters,ResultType::ObjectType,$order);

        $result = array();
        foreach ($this->datos as $row) {
            $tmpArray=array();
            $tmpArray[] = $row->getNombre();
            $result[]=$tmpArray;
        }
        return $result;

    }
    public function mapSorts()
    {
        return array('nombre');
    }
}