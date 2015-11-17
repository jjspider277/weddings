<?php

namespace backend\AdministracionBundle\Controller;

use backend\ComunBundle\Controller\MyCRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TipoParticipanteController extends MyCRUDController
{
    protected $type = 'backend\AdministracionBundle\Form\TipoParticipanteType';
    protected $textProperty = 'nombre';
    protected $view = '@Administracion/TipoParticipante/tipoParticipante_crud.html.twig';
    protected $tableModelService = 'admin.tipoparticipante.tm';
}
