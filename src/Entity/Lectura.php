<?php

namespace App\Entity;

use App\Repository\LecturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LecturaRepository::class)]
class Lectura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 2, nullable: true)]
    private ?string $lel_dato = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lel_fecha = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lel_estado = null;

    #[ORM\ManyToOne(inversedBy: 'lecturas')]
    private ?Medidor $medidor_id = null;

    #[ORM\OneToMany(targetEntity: DetalleConsumoLuz::class, mappedBy: 'lectura_anterior_id')]
    private Collection $detalleConsumoLuzs;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $lel_tipo = null;

    public function __construct()
    {
        $this->detalleConsumoLuzs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLelDato(): ?string
    {
        return $this->lel_dato;
    }

    public function setLelDato(?string $lel_dato): static
    {
        $this->lel_dato = $lel_dato;

        return $this;
    }

    public function getLelFecha(): ?\DateTimeInterface
    {
        return $this->lel_fecha;
    }

    public function setLelFecha(?\DateTimeInterface $lel_fecha): static
    {
        $this->lel_fecha = $lel_fecha;

        return $this;
    }

    public function isLelEstado(): ?bool
    {
        return $this->lel_estado;
    }

    public function setLelEstado(?bool $lel_estado): static
    {
        $this->lel_estado = $lel_estado;

        return $this;
    }

    public function getMedidorId(): ?Medidor
    {
        return $this->medidor_id;
    }

    public function setMedidorId(?Medidor $medidor_id): static
    {
        $this->medidor_id = $medidor_id;

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
            $detalleConsumoLuz->setLecturaAnteriorId($this);
        }

        return $this;
    }

    public function removeDetalleConsumoLuz(DetalleConsumoLuz $detalleConsumoLuz): static
    {
        if ($this->detalleConsumoLuzs->removeElement($detalleConsumoLuz)) {
            // set the owning side to null (unless already changed)
            if ($detalleConsumoLuz->getLecturaAnteriorId() === $this) {
                $detalleConsumoLuz->setLecturaAnteriorId(null);
            }
        }

        return $this;
    }

    public function getLelTipo(): ?string
    {
        return $this->lel_tipo;
    }

    public function setLelTipo(?string $lel_tipo): static
    {
        $this->lel_tipo = $lel_tipo;

        return $this;
    }
}
