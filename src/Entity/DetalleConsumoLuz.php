<?php

namespace App\Entity;

use App\Repository\DetalleConsumoLuzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetalleConsumoLuzRepository::class)]
class DetalleConsumoLuz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $dcl_consumo = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $dcl_tipo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $dcl_subtotal = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dcl_estado = null;

    #[ORM\ManyToOne(inversedBy: 'detalleConsumoLuzs')]
    private ?Lectura $lectura_anterior_id = null;

    #[ORM\ManyToOne(inversedBy: 'detalleConsumoLuzs')]
    private ?Lectura $lectura_actual_id = null;

    #[ORM\ManyToOne(inversedBy: 'detalleConsumoLuz')]
    private ?Recibo $recibo_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDclConsumo(): ?string
    {
        return $this->dcl_consumo;
    }

    public function setDclConsumo(?string $dcl_consumo): static
    {
        $this->dcl_consumo = $dcl_consumo;

        return $this;
    }

    public function getDclTipo(): ?string
    {
        return $this->dcl_tipo;
    }

    public function setDclTipo(?string $dcl_tipo): static
    {
        $this->dcl_tipo = $dcl_tipo;

        return $this;
    }

    public function getDclSubtotal(): ?string
    {
        return $this->dcl_subtotal;
    }

    public function setDclSubtotal(?string $dcl_subtotal): static
    {
        $this->dcl_subtotal = $dcl_subtotal;

        return $this;
    }

    public function isDclEstado(): ?bool
    {
        return $this->dcl_estado;
    }

    public function setDclEstado(?bool $dcl_estado): static
    {
        $this->dcl_estado = $dcl_estado;

        return $this;
    }

    public function getLecturaAnteriorId(): ?Lectura
    {
        return $this->lectura_anterior_id;
    }

    public function setLecturaAnteriorId(?Lectura $lectura_anterior_id): static
    {
        $this->lectura_anterior_id = $lectura_anterior_id;

        return $this;
    }

    public function getLecturaActualId(): ?Lectura
    {
        return $this->lectura_actual_id;
    }

    public function setLecturaActualId(?Lectura $lectura_actual_id): static
    {
        $this->lectura_actual_id = $lectura_actual_id;

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
}
