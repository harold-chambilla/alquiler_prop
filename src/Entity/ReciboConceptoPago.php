<?php

namespace App\Entity;

use App\Repository\ReciboConceptoPagoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReciboConceptoPagoRepository::class)]
class ReciboConceptoPago
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $rcp_fecha_digitacion = null;

    #[ORM\ManyToOne(inversedBy: 'reciboConceptoPagos')]
    private ?Recibo $recibo_id = null;

    #[ORM\ManyToOne(inversedBy: 'reciboConceptoPagos', cascade: ['persist'])]
    private ?ConceptoPago $concepto_pago_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRcpFechaDigitacion(): ?\DateTimeInterface
    {
        return $this->rcp_fecha_digitacion;
    }

    public function setRcpFechaDigitacion(?\DateTimeInterface $rcp_fecha_digitacion): static
    {
        $this->rcp_fecha_digitacion = $rcp_fecha_digitacion;

        return $this;
    }

    public function getReciboId(): ?Recibo
    {
        return $this->recibo_id;
    }

    public function setReciboId(?Recibo $recibo_id): static
    {
        $this->recibo_id = $recibo_id;

        return $this;
    }

    public function getConceptoPagoId(): ?ConceptoPago
    {
        return $this->concepto_pago_id;
    }

    public function setConceptoPagoId(?ConceptoPago $concepto_pago_id): static
    {
        $this->concepto_pago_id = $concepto_pago_id;

        return $this;
    }
}
