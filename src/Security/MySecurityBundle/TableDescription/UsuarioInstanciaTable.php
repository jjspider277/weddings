<?php

namespace Security\MySecurityBundle\TableDescription;


use backend\ComunBundle\tableDescription\architecture\ColumnaGrid;
use backend\ComunBundle\tableDescription\architecture\GridColumn;
use backend\ComunBundle\tableDescription\architecture\SelectFilterColumn;
use backend\ComunBundle\tableDescription\architecture\TableModel;
use backend\ComunBundle\Util\ResultType;
use backend\ComunBundle\Util\Util;
use backend\ComunBundle\Util\UtilRepository2;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAware;

class UsuarioInstanciaTable extends TableModel
{

    function __construct() {
        parent::__construct();
        $this->entity = 'Security\MySecurityBundle\EP\UserInstanceEP';
    }
    public function defineColumns() {
        $nameColumn = new GridColumn("Tipo de instancia", '30%','tipoInstancia');
        $nameColumn->setSortable(false);
        $nameColumn->setFilterType("select");
        $filterData = new SelectFilterColumn();//EL showValue por defecto es denominacion y el idValue por defecto es Id
        $filterData->setData( $this->getEm()->getRepository('BaseBundle:TipoInstancia')->findAll());
        $nameColumn->setFilterData($filterData);
        $this->columns[] =$nameColumn;

        $nameColumn = new GridColumn("Instancia", '30%','instancia');
        $nameColumn->setSortable(false);
        $nameColumn->setFilterType("select");
        $filterData = new SelectFilterColumn();//EL showValue por defecto es denominacion y el idValue por defecto es Id
        $filterData->setGrouped(true);
        $r = array();
        $r['Oficialías'] = $this->getEm()->getRepository('BaseBundle:Oficialia')->getByPoderJudicialLogged(array(),ResultType::ArrayType,'denominacion');
        $r['Juzgados'] = $this->getEm()->getRepository('BaseBundle:Juzgado')->getByPoderJudicialLogged(array(),ResultType::ArrayType,'denominacion');
        $filterData->setData($r);
        $nameColumn->setFilterData($filterData);
        $this->columns[] =$nameColumn;

    }
    public function defineActions()
    {
        $actions = parent::defineActions();
        return $actions;
    }
    public function defineRutas()
    {
        $rutas =new GroupRutas();
        $rutas->setNew('security_crud_user_assign_instancia_new');
        $rutas->setDelete('security_crud_user_assign_instancia_delete');
//        $rutas->setDetails('security_crud_group_details');
        $rutas->setList('security_crud_user_assign_instancia_listAjax');
        return $rutas;
    }
    public function constructData()
    {
        $this->datos= $this->getRepo("MySecurityBundle:Usuario")->getInstances(UtilRepository2::getParameter('idUser'),$this->getTableFiltersByRquest());

        $result = array();
        foreach ($this->datos as $row) {
            $tmpArray=array();
            $tmpArray[] = $row['tipoInstancia'];
            $tmpArray[] = $row['instancia'];
            $result[]=$tmpArray;
        }
        return $result;

    }
}