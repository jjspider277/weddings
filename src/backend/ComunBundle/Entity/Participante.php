<?php
namespace backend\ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dia
 *
 * @ORM\Table(name="participante")
 * @ORM\Entity()
 */
class Participante
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="participante_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=10, nullable=false)
     */
    private $nombre;

    /**
     * @var \Boda
     *
     * @ORM\ManyToOne(targetEntity="Boda",inversedBy="novio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="boda", referencedColumnName="id")
     * })
     */
    private $boda;



    /**
     * @var \tipoparticipante
     *
     * @ORM\ManyToOne(targetEntity="TipoParticipante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipoparticipante", referencedColumnName="id")
     * })
     */
    private $tipoParticipante;

    /**
     * Get id
     *
     * @return integer
     */

    /**
     * @var \Security\MySecurityBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="Security\MySecurityBundle\Entity\Usuario", inversedBy="profesor",cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id",onDelete="SET NULL")
     * })
     */
    private $usuario;

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