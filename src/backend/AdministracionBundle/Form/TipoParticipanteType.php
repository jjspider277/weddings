<?php

namespace backend\AdministracionBundle\Form;

use backend\ComunBundle\Util\ResultType;
use backend\AdministracionBundle\Repository\TipoParticipanteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoParticipanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        ld($options['data']->getHora() == null ? null: array('hour'=>$options['data']->getHora()->format('H'),'minute'=>$options['data']->getHora()->format('i')));
        $builder
            ->add('nombre',null,array('label'=>'Nombre:*','max_length'=> 5));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'backend\ComunBundle\Entity\TipoParticipante'
        ));
    }

    public function getName()
    {
        return '_adminbundle_tipoparticipante_type';
    }
}
