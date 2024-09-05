<?php

namespace App\Entity;

use App\Repository\ReciboRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReciboRepository::class)]
class Recibo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $re_codigo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $re_fecha_emision = null;

    #[ORM\Column(nullable: true)]
    private ?bool $re_estado = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $re_pago_total = null;

    #[ORM\ManyToOne(inversedBy: 'recibos')]
    private ?Contrato $contrato_id = null;

    #[ORM\OneToMany(targetEntity: ReciboConceptoPago::class, mappedBy: 'recibo_id')]
    private Collection $reciboConceptoPagos;

    /**
     * @var Collection<int, DetalleConsumoLuz>
     */
    #[ORM\OneToMany(targetEntity: DetalleConsumoLuz::class, mappedBy: 'recibo_id')]
    private Collection $detalleConsumoLuzs;

    /**
     * @var Collection<int, DetalleConsumoLuz>
     */
    #[ORM\OneToMany(targetEntity: DetalleConsumoLuz::class, mappedBy: 'recibo_id')]
    private Collection $detalleConsumoLuz;

    public function __construct()
    {
        $this->reciboConceptoPagos = new ArrayCollection();
        $this->detalleConsumoLuzs = new ArrayCollection();
        $this->detalleConsumoLuz = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReCodigo(): ?string
    {
        return $this->re_codigo;
    }

    public function setReCodigo(?string $re_codigo): static
    {
        $this->re_codigo = $re_codigo;

        return $this;
    }

    public function getReFechaEmision(): ?\DateTimeInterface
    {
        return $this->re_fecha_emision;
    }

    public function setReFechaEmision(?\DateTimeInterface $re_fecha_emision): static
    {
        $this->re_fecha_emision = $re_fecha_emision;

        return $this;
    }

    public function isReEstado(): ?bool
    {
        return $this->re_estado;
    }

    public function setReEstado(?bool $re_estado): static
    {
        $this->re_estado = $re_estado;

        return $this;
    }

    public function getRePagoTotal(): ?string
    {
        return $this->re_pago_total;
    }

    public function setRePagoTotal(?string $re_pago_total): static
    {
        $this->re_pago_total = $re_pago_total;

        return $this;
    }


    public function getContratoId(): ?Contrato
    {
        return $this->contrato_id;
    }

    public function setContratoId(?Contrato $contrato_id): static
    {
        $this->contrato_id = $contrato_id;

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
            $reciboConceptoPago->setReciboId($this);
        }

        return $this;
    }

    public function removeReciboConceptoPago(ReciboConceptoPago $reciboConceptoPago): static
    {
        if ($this->reciboConceptoPagos->removeElement($reciboConceptoPago)) {
            // set the owning side to null (unless already changed)
            if ($reciboConceptoPago->getReciboId() === $this) {
                $reciboConceptoPago->setReciboId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetalleConsumoLuz>
     */
    public function getDetalleConsumoLuzs(): Collection
    {
        return $this->detalleConsumoLuzs;
    }

    public function addDetalleConsumoLuz(DetalleConsumoLuz $detalleConsumoLuz): static
    {
        if (!$this->detalleConsumoLuzs->contains($detalleConsumoLuz)) {
            $this->detalleConsumoLuzs->add($detalleConsumoLuz);
            $detalleConsumoLuz->setReciboId($this);
        }

        return $this;
    }

    public function removeDetalleConsumoLuz(DetalleConsumoLuz $detalleConsumoLuz): static
    {
        if ($this->detalleConsumoLuzs->removeElement($detalleConsumoLuz)) {
            // set the owning side to null (unless already changed)
            if ($detalleConsumoLuz->getReciboId() === $this) {
                $detalleConsumoLuz->setReciboId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetalleConsumoLuz>
     */
    public function getDetalleConsumoLuz(): Collection
    {
        return $this->detalleConsumoLuz;
    }
}
