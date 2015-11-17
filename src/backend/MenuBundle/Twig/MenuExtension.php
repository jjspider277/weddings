<?php

namespace backend\MenuBundle\Twig;

use backend\ComunBundle\Util\UtilRepository2;

class MenuExtension extends  \Twig_Extension{

    public function getFunctions()
    {
        return array(
            'getMenu' => new \Twig_Function_Method($this, 'getMenu'),
            'getMenuParents' => new \Twig_Function_Method($this, 'getMenuParents')
        );
    }
    public function getMenu()
    {
//        ldd(UtilRepository2::getRepo('ADEPSOFTMenuBundle:Menu')->obtenerHojas());
        $menu = UtilRepository2::getRepo('MenuBundle:Menu')->filterObjects(array('padre'=>-1,'id'=>array('!=',-1)));
//        $menu = UtilRepository2::getRepo('ADEPSOFTMenuBundle:Menu')->filter(array('padre'=>-1,'id'=>array('!=',-1)),null,ResultType::IDSType);
        return $menu;

    }

    public function getMenuParents()
    {
        $padres =  UtilRepository2::getRepo('MenuBundle:Menu')->obtenerMenusPadresXUsuarioLogueado();
        return $padres;
    }
    public function getName()
    {
        return 'menu_extension';
    }

} 