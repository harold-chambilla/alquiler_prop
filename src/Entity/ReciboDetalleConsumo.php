<?php

namespace App\Entity;

use App\Repository\ReciboDetalleConsumoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReciboDetalleConsumoRepository::class)]
class ReciboDetalleConsumo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $rdc_consumo = null;

    #[ORM\Column(nullable: true)]
    private ?int $rdc_tipo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $rdc_subtotal = null;

    #[ORM\Column(nullable: true)]
    private ?bool $rdc_estado = null;

    #[ORM\Column]
    private ?int $lect_ant_id = null;

    #[ORM\Column]
    private ?int $lec_act_id = null;

    #[ORM\ManyToOne(inversedBy: 'reciboDetalleConsumos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recibo $recibo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRdcConsumo(): ?string
    {
        return $this->rdc_consumo;
    }

    public function setRdcConsumo(?string $rdc_consumo): static
    {
        $this->rdc_consumo = $rdc_consumo;

        return $this;
    }

    public function getRdcTipo(): ?int
    {
        return $this->rdc_tipo;
    }

    public function setRdcTipo(?int $rdc_tipo): static
    {
        $this->rdc_tipo = $rdc_tipo;

        return $this;
    }

    public function getRdcSubtotal(): ?string
    {
        return $this->rdc_subtotal;
    }

    public function setRdcSubtotal(?string $rdc_subtotal): static
    {
        $this->rdc_subtotal = $rdc_subtotal;

        return $this;
    }

    public function isRdcEstado(): ?bool
    {
        return $this->rdc_estado;
    }

    public function setRdcEstado(?bool $rdc_estado): static
    {
        $this->rdc_estado = $rdc_estado;

        return $this;
    }

    public function getLectAntId(): ?int
    {
        return $this->lect_ant_id;
    }

    public function setLectAntId(int $lect_ant_id): static
    {
        $this->lect_ant_id = $lect_ant_id;

        return $this;
    }

    public function getLecActId(): ?int
    {
        return $this->lec_act_id;
    }

    public function setLecActId(int $lec_act_id): static
    {
        $this->lec_act_id = $lec_act_id;

        return $this;
    }

    public function getRecibo(): ?Recibo
    {
        return $this->recibo;
    }

    public function setRecibo(?Recibo $recibo): static
    {
        $this->recibo = $recibo;

        return $this;
    }
}
