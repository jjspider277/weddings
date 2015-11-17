<?php
namespace backend\ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Dia
 *
 * @ORM\Table(name="programa")
 * @ORM\Entity(repositoryClass="")*/
class Programa
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="programa_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=10, nullable=false)
     */
    private $nombre;


    /**
     * @var \Evento
     *
     * @ORM\OneToMany(targetEntity="Evento",mappedBy="programa")
     */
    private $evento;

    /**
     * @ORM\OneToOne(targetEntity="Boda",inversedBy="programa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="boda", referencedColumnName="id")
     * })
     */
    private $programa;

    public function __construct(){
        $this->evento = new \Doctrine\Common\Collections\ArrayCollection();
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

}