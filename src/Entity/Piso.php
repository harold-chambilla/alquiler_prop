<?php

namespace App\Entity;

use App\Repository\PisoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PisoRepository::class)]
class Piso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $pi_posicion = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $pi_cuarto = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $pi_zona = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pi_estado = null;

    #[ORM\ManyToOne(inversedBy: 'pisos')]
    private ?Residencia $residencia_id = null;

    /**
     * @var Collection<int, Contrato>
     */
    #[ORM\OneToMany(targetEntity: Contrato::class, mappedBy: 'piso_id')]
    private Collection $contratos;

    /**
     * @var Collection<int, Medidor>
     */
    #[ORM\OneToMany(targetEntity: Medidor::class, mappedBy: 'piso', orphanRemoval: true)]
    private Collection $medidors;

    public function __toString(): string
    {
        return $this->pi_cuarto . ' ' . $this->pi_zona;
    }

    public function __construct()
    {
        $this->contratos = new ArrayCollection();
        $this->medidors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPiPosicion(): ?string
    {
        return $this->pi_posicion;
    }

    public function setPiPosicion(?string $pi_posicion): static
    {
        $this->pi_posicion = $pi_posicion;

        return $this;
    }

    public function getPiCuarto(): ?string
    {
        return $this->pi_cuarto;
    }

    public function setPiCuarto(?string $pi_cuarto): static
    {
        $this->pi_cuarto = $pi_cuarto;

        return $this;
    }

    public function getPiZona(): ?string
    {
        return $this->pi_zona;
    }

    public function setPiZona(?string $pi_zona): static
    {
        $this->pi_zona = $pi_zona;

        return $this;
    }

    public function isPiEstado(): ?bool
    {
        return $this->pi_estado;
    }

    public function setPiEstado(?bool $pi_estado): static
    {
        $this->pi_estado = $pi_estado;

        return $this;
    }

    public function getResidenciaId(): ?Residencia
    {
        return $this->residencia_id;
    }

    public function setResidenciaId(?Residencia $residencia_id): static
    {
        $this->residencia_id = $residencia_id;

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
            $contrato->setPisoId($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): static
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getPisoId() === $this) {
                $contrato->setPisoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Medidor>
     */
    public function getMedidors(): Collection
    {
        return $this->medidors;
    }

    public function addMedidor(Medidor $medidor): static
    {
        if (!$this->medidors->contains($medidor)) {
            $this->medidors->add($medidor);
            $medidor->setPiso($this);
        }

        return $this;
    }

    public function removeMedidor(Medidor $medidor): static
    {
        if ($this->medidors->removeElement($medidor)) {
            // set the owning side to null (unless already changed)
            if ($medidor->getPiso() === $this) {
                $medidor->setPiso(null);
            }
        }

        return $this;
    }
}
