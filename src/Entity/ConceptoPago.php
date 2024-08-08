<?php

namespace App\Entity;

use App\Repository\ConceptoPagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConceptoPagoRepository::class)]
class ConceptoPago
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $cop_nombre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cop_descripcion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $cop_precio = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cop_estado = null;

    #[ORM\OneToMany(targetEntity: ReciboConceptoPago::class, mappedBy: 'concepto_pago_id')]
    private Collection $reciboConceptoPagos;

    public function __construct()
    {
        $this->reciboConceptoPagos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCopNombre(): ?string
    {
        return $this->cop_nombre;
    }

    public function setCopNombre(?string $cop_nombre): static
    {
        $this->cop_nombre = $cop_nombre;

        return $this;
    }

    public function getCopDescripcion(): ?string
    {
        return $this->cop_descripcion;
    }

    public function setCopDescripcion(?string $cop_descripcion): static
    {
        $this->cop_descripcion = $cop_descripcion;

        return $this;
    }

    public function getCopPrecio(): ?string
    {
        return $this->cop_precio;
    }

    public function setCopPrecio(?string $cop_precio): static
    {
        $this->cop_precio = $cop_precio;

        return $this;
    }

    public function isCopEstado(): ?bool
    {
        return $this->cop_estado;
    }

    public function setCopEstado(?bool $cop_estado): static
    {
        $this->cop_estado = $cop_estado;

        return $this;
    }

    /**
     * @return Collection<int, ReciboConceptoPago>
     */
    public function getReciboConceptoPagos(): Collection
    {
        return $this->reciboConceptoPagos;
    }

    public function addReciboConceptoPago(ReciboConceptoPago $reciboConceptoPago): static
    {
        if (!$this->reciboConceptoPagos->contains($reciboConceptoPago)) {
            $this->reciboConceptoPagos->add($reciboConceptoPago);
            $reciboConceptoPago->setConceptoPagoId($this);
        }

        return $this;
    }

    public function removeReciboConceptoPago(ReciboConceptoPago $reciboConceptoPago): static
    {
        if ($this->reciboConceptoPagos->removeElement($reciboConceptoPago)) {
            // set the owning side to null (unless already changed)
            if ($reciboConceptoPago->getConceptoPagoId() === $this) {
                $reciboConceptoPago->setConceptoPagoId(null);
            }
        }

        return $this;
    }
}
