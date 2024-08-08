<?php

namespace App\Entity;

use App\Repository\MedidorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedidorRepository::class)]
class Medidor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $mel_codigo = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $mel_tipo = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $mel_marca = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $mel_año = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mel_fecha_compra = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mel_fecha_instalacion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mel_fecha_desinstalacion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mel_estado = null;

    #[ORM\OneToMany(targetEntity: Lectura::class, mappedBy: 'medidor_id')]
    private Collection $lecturas;

    public function __construct()
    {
        $this->lecturas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMelCodigo(): ?string
    {
        return $this->mel_codigo;
    }

    public function setMelCodigo(?string $mel_codigo): static
    {
        $this->mel_codigo = $mel_codigo;

        return $this;
    }

    public function getMelTipo(): ?string
    {
        return $this->mel_tipo;
    }

    public function setMelTipo(?string $mel_tipo): static
    {
        $this->mel_tipo = $mel_tipo;

        return $this;
    }

    public function getMelMarca(): ?string
    {
        return $this->mel_marca;
    }

    public function setMelMarca(?string $mel_marca): static
    {
        $this->mel_marca = $mel_marca;

        return $this;
    }

    public function getMelAño(): ?string
    {
        return $this->mel_año;
    }

    public function setMelAño(?string $mel_año): static
    {
        $this->mel_año = $mel_año;

        return $this;
    }

    public function getMelFechaCompra(): ?\DateTimeInterface
    {
        return $this->mel_fecha_compra;
    }

    public function setMelFechaCompra(?\DateTimeInterface $mel_fecha_compra): static
    {
        $this->mel_fecha_compra = $mel_fecha_compra;

        return $this;
    }

    public function getMelFechaInstalacion(): ?\DateTimeInterface
    {
        return $this->mel_fecha_instalacion;
    }

    public function setMelFechaInstalacion(?\DateTimeInterface $mel_fecha_instalacion): static
    {
        $this->mel_fecha_instalacion = $mel_fecha_instalacion;

        return $this;
    }

    public function getMelFechaDesinstalacion(): ?\DateTimeInterface
    {
        return $this->mel_fecha_desinstalacion;
    }

    public function setMelFechaDesinstalacion(?\DateTimeInterface $mel_fecha_desinstalacion): static
    {
        $this->mel_fecha_desinstalacion = $mel_fecha_desinstalacion;

        return $this;
    }

    public function isMelEstado(): ?bool
    {
        return $this->mel_estado;
    }

    public function setMelEstado(?bool $mel_estado): static
    {
        $this->mel_estado = $mel_estado;

        return $this;
    }

    /**
     * @return Collection<int, Lectura>
     */
    public function getLecturas(): Collection
    {
        return $this->lecturas;
    }

    public function addLectura(Lectura $lectura): static
    {
        if (!$this->lecturas->contains($lectura)) {
            $this->lecturas->add($lectura);
            $lectura->setMedidorId($this);
        }

        return $this;
    }

    public function removeLectura(Lectura $lectura): static
    {
        if ($this->lecturas->removeElement($lectura)) {
            // set the owning side to null (unless already changed)
            if ($lectura->getMedidorId() === $this) {
                $lectura->setMedidorId(null);
            }
        }

        return $this;
    }
}
