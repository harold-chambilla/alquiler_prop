<?php

namespace App\Entity;

use App\Repository\ContratoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ContratoRepository::class)]
#[Assert\Cascade]
class Contrato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $co_fecha_ingreso = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $co_fecha_vencimiento = null;

    #[ORM\ManyToOne(inversedBy: 'contratos', cascade: ['persist'])]
    private ?Arrendatario $arrendatario_id = null;

    #[ORM\ManyToOne(inversedBy: 'contratos')]
    private ?Usuario $usuario_id = null;

    #[ORM\ManyToOne(inversedBy: 'contratos')]
    private ?Residencia $residencia_id = null;

    #[ORM\OneToMany(targetEntity: Recibo::class, mappedBy: 'contrato_id')]
    private Collection $recibos;

    #[ORM\ManyToOne(inversedBy: 'contratos')]
    private ?Piso $piso_id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $co_alquiler_mensual = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $co_agua = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $co_fecha_actual = null;

    public function __toString(): string
    {
        return $this->arrendatario_id . ' ' . $this->co_alquiler_mensual;
    }
    #[ORM\Column]
    private ?bool $co_estado = null;

    public function __construct()
    {
        $this->recibos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoFechaIngreso(): ?\DateTimeInterface
    {
        return $this->co_fecha_ingreso;
    }

    public function setCoFechaIngreso(?\DateTimeInterface $co_fecha_ingreso): static
    {
        $this->co_fecha_ingreso = $co_fecha_ingreso;

        return $this;
    }

    public function getCoFechaVencimiento(): ?\DateTimeInterface
    {
        return $this->co_fecha_vencimiento;
    }

    public function setCoFechaVencimiento(?\DateTimeInterface $co_fecha_vencimiento): static
    {
        $this->co_fecha_vencimiento = $co_fecha_vencimiento;

        return $this;
    }

    public function getArrendatarioId(): ?Arrendatario
    {
        return $this->arrendatario_id;
    }

    public function setArrendatarioId(?Arrendatario $arrendatario_id): static
    {
        $this->arrendatario_id = $arrendatario_id;

        return $this;
    }

    public function getUsuarioId(): ?Usuario
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(?Usuario $usuario_id): static
    {
        $this->usuario_id = $usuario_id;

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
     * @return Collection<int, Recibo>
     */
    public function getRecibos(): Collection
    {
        return $this->recibos;
    }

    public function addRecibo(Recibo $recibo): static
    {
        if (!$this->recibos->contains($recibo)) {
            $this->recibos->add($recibo);
            $recibo->setContratoId($this);
        }

        return $this;
    }

    public function removeRecibo(Recibo $recibo): static
    {
        if ($this->recibos->removeElement($recibo)) {
            // set the owning side to null (unless already changed)
            if ($recibo->getContratoId() === $this) {
                $recibo->setContratoId(null);
            }
        }

        return $this;
    }

    public function getPisoId(): ?Piso
    {
        return $this->piso_id;
    }

    public function setPisoId(?Piso $piso_id): static
    {
        $this->piso_id = $piso_id;

        return $this;
    }

    public function getCoAlquilerMensual(): ?string
    {
        return $this->co_alquiler_mensual;
    }

    public function setCoAlquilerMensual(?string $co_alquiler_mensual): static
    {
        $this->co_alquiler_mensual = $co_alquiler_mensual;

        return $this;
    }

    public function getCoAgua(): ?string
    {
        return $this->co_agua;
    }

    public function setCoAgua(?string $co_agua): static
    {
        $this->co_agua = $co_agua;

        return $this;
    }

    public function getCoFechaActual(): ?\DateTimeInterface
    {
        return $this->co_fecha_actual;
    }

    public function setCoFechaActual(?\DateTimeInterface $co_fecha_actual): static
    {
        $this->co_fecha_actual = $co_fecha_actual;
        
        return $this;
    }
    public function isCoEstado(): ?bool
    {
        return $this->co_estado;
    }

    public function setCoEstado(bool $co_estado): static
    {
        $this->co_estado = $co_estado;

        return $this;
    }
}
