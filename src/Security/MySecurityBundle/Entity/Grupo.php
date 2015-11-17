<?php

namespace Security\MySecurityBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DocAssert;
use FOS\UserBundle\Entity\Group;

/**
 * Rol
 *
 * @ORM\Table(name="seguridad.grupo")
 * @ORM\HasLifecycleCallbacks()
 * @DocAssert\UniqueEntity(fields={"name"}, message="Ya existe un rol con ese nombre.")
 * @ORM\Entity(repositoryClass="Security\MySecurityBundle\Repository\GrupoRepository")
 */
class Grupo extends Group
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="seguridad.grupo_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="grupos")
     * @ORM\JoinTable(name="seguridad.grupo_permiso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="grupo", referencedColumnName="id", onDelete="cascade")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permiso", referencedColumnName="id", onDelete="cascade")
     *   }
     * )
     */
    private $permisos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="groups")
     */
    private $usuarios;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPermisionListString()
    {
        $string = '';
        $i=0;
        foreach($this->permiso as $permiso)
        {
            if($i == 0)
                $string.=$permiso->getDenominacion();
            else
                $string .= ", ".$permiso->getDenominacion();
            $i++;
        }
        return $string;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function resetRoles()
    {
        $this->roles = array();
        foreach($this->getPermisos() as $permiso){
            $this->addRole($permiso->getPermiso());
        }
    }

    /**
     * Add permiso
     *
     * @param \Security\MySecurityBundle\Entity\Permission $permiso
     * @return Grupo
     */
    public function addPermiso(\Security\MySecurityBundle\Entity\Permission $permiso)
    {
        $this->permisos[] = $permiso;
    
        return $this;
    }

    /**
     * Remove permiso
     *
     * @param \Security\MySecurityBundle\Entity\Permission $permiso
     */
    public function removePermiso(\Security\MySecurityBundle\Entity\Permission $permiso)
    {
        $this->permisos->removeElement($permiso);
    }

    /**
     * Get permiso
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermisos()
    {
        return $this->permisos;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permisos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add usuarios
     *
     * @param \Security\MySecurityBundle\Entity\Usuario $usuarios
     * @return Grupo
     */
    public function addUsuario(\Security\MySecurityBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \Security\MySecurityBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\Security\MySecurityBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
}