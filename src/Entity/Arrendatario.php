<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArrendatarioRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ArrendatarioRepository::class)]
#[UniqueEntity(fields: ['ao_cedula_identidad'], message: 'Esta cédula de identidad ya está en uso.')]
#[UniqueEntity(fields: ['ao_telefono'], message: 'Este teléfono ya está en uso.')]
/**
 * @Assert\EnableAutoMapping()
 */
class Arrendatario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $ao_nombres = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $ao_apellidos = null;

    #[ORM\Column(length: 16, nullable: true, unique:true)]
    private ?string $ao_telefono = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $ao_tipo = null;

    #[ORM\Column(length: 16, unique:true)]
    private ?string $ao_cedula_identidad = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ao_fecha_nacimiento = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ao_foto_dni = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ao_foto = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ao_estado = null;

    #[ORM\OneToMany(targetEntity: Contrato::class, mappedBy: 'arrendatario_id')]
    private Collection $contratos;
    public function __toString(): string
    {
        return $this->ao_nombres . ' ' . $this->ao_apellidos;
    }
    
    public function __construct()
    {
        $this->contratos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAoNombres(): ?string
    {
        return $this->ao_nombres;
    }

    public function setAoNombres(?string $ao_nombres): static
    {
        $this->ao_nombres = $ao_nombres;

        return $this;
    }

    public function getAoApellidos(): ?string
    {
        return $this->ao_apellidos;
    }

    public function setAoApellidos(?string $ao_apellidos): static
    {
        $this->ao_apellidos = $ao_apellidos;

        return $this;
    }

    public function getAoTelefono(): ?string
    {
        return $this->ao_telefono;
    }

    public function setAoTelefono(?string $ao_telefono): static
    {
        $this->ao_telefono = $ao_telefono;

        return $this;
    }

    public function getAoTipo(): ?string
    {
        return $this->ao_tipo;
    }

    public function setAoTipo(?string $ao_tipo): static
    {
        $this->ao_tipo = $ao_tipo;

        return $this;
    }

    public function getAoCedulaIdentidad(): ?string
    {
        return $this->ao_cedula_identidad;
    }

    public function setAoCedulaIdentidad(?string $ao_cedula_identidad): static
    {
        $this->ao_cedula_identidad = $ao_cedula_identidad;

        return $this;
    }

    public function getAoFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->ao_fecha_nacimiento;
    }

    public function setAoFechaNacimiento(?\DateTimeInterface $ao_fecha_nacimiento): static
    {
        $this->ao_fecha_nacimiento = $ao_fecha_nacimiento;

        return $this;
    }

    public function getAoFotoDni(): ?string
    {
        return $this->ao_foto_dni;
    }

    public function setAoFotoDni(?string $ao_foto_dni): static
    {
        $this->ao_foto_dni = $ao_foto_dni;

        return $this;
    }

    public function getAoFoto(): ?string
    {
        return $this->ao_foto;
    }

    public function setAoFoto(?string $ao_foto): static
    {
        $this->ao_foto = $ao_foto;

        return $this;
    }

    public function isAoEstado(): ?bool
    {
        return $this->ao_estado;
    }

    public function setAoEstado(?bool $ao_estado): static
    {
        $this->ao_estado = $ao_estado;

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
            $contrato->setArrendatarioId($this);
        }

        return $this;
    }

    public function removeContrato(Contrato $contrato): static
    {
        if ($this->contratos->removeElement($contrato)) {
            // set the owning side to null (unless already changed)
            if ($contrato->getArrendatarioId() === $this) {
                $contrato->setArrendatarioId(null);
            }
        }

        return $this;
    }
}
