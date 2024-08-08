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

    #[ORM\OneToMany(targetEntity: Contrato::class, mappedBy: 'residencia_id')]
    private Collection $contratos;

    #[ORM\OneToMany(targetEntity: Piso::class, mappedBy: 'residencia_id')]
    private Collection $pisos;

    public function __construct()
    {
        $this->contratos = new ArrayCollection();
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
     * @return Collection<int, Contrato>
     */
    public function getContratos(): Collection
    {
        return $this->contratos;
    }

    public function addContrato(Contrato $contrato): static
    {
        if (!$this->contratos->contains($contrato)) {
            $this->contratos->add($contrato);
            $contrato->setResidenciaId($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): static
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getResidenciaId() === $this) {
                $contrato->setResidenciaId(null);
            }
        }

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
}
