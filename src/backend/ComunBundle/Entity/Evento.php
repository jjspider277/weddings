<?php
namespace backend\ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dia
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity(repositoryClass="backend\ComunBundle\Util\NomencladoresRepository")
 */
class Evento
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="evento_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="text",  nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha", type="datetime",  nullable=false)
     */
    private $fecha;


    /**
     * @var \Evento
     *
     * @ORM\ManyToOne(targetEntity="TipoEvento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipoevento", referencedColumnName="id")
     * })
     */
    private $tipoEvento;

    /**
     * @var \Lugar
     *
     * @ORM\OneToOne(targetEntity="Lugar",inversedBy="evento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lugar", referencedColumnName="id")
     * })
     */
    private $lugar;

    /**
     * @var \Programa
     *
     * @ORM\ManyToOne(targetEntity="Programa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="programa", referencedColumnName="id")
     * })
     */
    private $programa;
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

}