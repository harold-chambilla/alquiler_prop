<?php

namespace App\Entity;

use App\Repository\ResidenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResidenciaRepository::class)]
class Residencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $res_direccion = null;

    #[ORM\OneToMany(targetEntity: Piso::class, mappedBy: 'residencia_id')]
    private Collection $pisos;

    #[ORM\ManyToOne(inversedBy: 'residencia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    public function __construct()
    {
        $this->pisos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResDireccion(): ?string
    {
        return $this->res_direccion;
    }

    public function setResDireccion(?string $res_direccion): static
    {
        $this->res_direccion = $res_direccion;

        return $this;
    }

    /**
     * @return Collection<int, Piso>
     */
    public function getPisos(): Collection
    {
        return $this->pisos;
    }

    public function addPiso(Piso $piso): static
    {
        if (!$this->pisos->contains($piso)) {
            $this->pisos->add($piso);
            $piso->setResidenciaId($this);
        }

        return $this;
    }

    public function removePiso(Piso $piso): static
    {
        if ($this->pisos->removeElement($piso)) {
            // set the owning side to null (unless already changed)
            if ($piso->getResidenciaId() === $this) {
                $piso->setResidenciaId(null);
            }
        }

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}
