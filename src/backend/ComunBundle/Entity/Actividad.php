<?php
namespace backend\ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="backend\ComunBundle\Util\NomencladoresRepository")
 * @ORM\Table(name="Actividad")
 */
class Boda
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="actividad_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=10, nullable=false)
     */
    private $nombre;

    /**
     * @var \Novio
     *
     * @ORM\OneToMany(targetEntity="participante",mappedBy="boda")
     */
    private $participante;

    /**
     * @var \Evento
     *
     * @ORM\ManyToOne(targetEntity="TipoActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipoactividad", referencedColumnName="id")
     * })
     */
    private $tipoEvento;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participante = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Dia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Get tipoEvento
     *
     * @return \backend\ComunBundle\Entity\TipoEvento
     */
    public function getTipoEvento()
    {
        return $this->tipoEvento;
    }

    /**
     * Set tipoEvento
     *
     * @param \backend\ComunBundle\Entity\TipoEvento tipoEvento
     * @return TipoEvento
     */
    public function setTipoEvento(\backend\ComunBundle\Entity\TipoEvento $tipoEvento= null)
    {
        $this->tipoEvento = $tipoEvento;
        return $this;
    }
}