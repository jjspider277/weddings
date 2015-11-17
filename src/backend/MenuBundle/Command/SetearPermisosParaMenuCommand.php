<?php
namespace  backend\MenuBundle\Command;

use backend\ComunBundle\Util\UtilRepository2Config;
use backend\MenuBundle\Entity\Menu;
use backend\MenuBundle\Entity\Permiso;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;


class SetearPermisosParaMenuCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this->setName('setearPermisosParaMenu')->setDefinition(array(
            new InputOption(
                'security_yml', null, InputOption::VALUE_OPTIONAL,
                'Ruta al archivo de seguridad'
            )));
    }
    protected  function execute(InputInterface $input, OutputInterface $output){
        UtilRepository2Config::$output = $output;
        $output->write("Ejecutando\n");

        $file = $input->getOption('security_yml');
        if($file == null)
            $file = __DIR__.'/../../../../app/config/security.yml';


        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repoMenu = $em->getRepository("ADEPSOFTMenuBundle:Menu");
//        ldd($repoMenu->obtenerHojas());

        $repoMenu->setPermisosForRoutes($file);

        $output->write("Completado\n");
    }
}

?>