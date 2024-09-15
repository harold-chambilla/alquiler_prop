<?php

namespace App\DataFixtures;

use App\Entity\Piso;
use App\Entity\Recibo;
use App\Entity\Lectura;
use App\Entity\Medidor;
use App\Entity\Usuario;
use App\Entity\Contrato;
use App\Entity\Residencia;
use App\Entity\Arrendatario;
use App\Entity\ConceptoPago;
use App\Entity\DetalleConsumoLuz;
use App\Entity\ReciboConceptoPago;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Crear usuarios admin y user
        
        $admin = new Usuario();
        $admin->setEmail('admin@ksperu.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword($admin, '123456'));
        $manager->persist($admin);

        $user = new Usuario();
        $user->setEmail('user@ksperu.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword($user, '123456'));
        $manager->persist($user);

        $arrendatarios = [];
        for ($i = 1; $i <= 3; $i++) {
            $arrendatario = new Arrendatario();
            $arrendatario->setAoNombres('Nombre '.$i)
                ->setAoApellidos('Apellido '.$i)
                ->setAoTelefono('98765432'.$i)
                ->setAoTipo('Tipo '.$i)
                ->setAoCedulaIdentidad('7654321'.$i)
                ->setAoFechaNacimiento(new \DateTime('1980-05-10'))
                ->setAoFotoDni(NULL)
                ->setAoFoto(NULL)
                ->setAoEstado(true);
            $manager->persist($arrendatario);
            $arrendatarios[] = $arrendatario;
        }

        $residencias = [];
        for ($i = 1; $i <= 3; $i++) {
            $residencia = new Residencia();
            $residencia->setUsuario($i === 1 ? $admin : $user)
                ->setResDireccion('Calle Falsa '.$i);
            $manager->persist($residencia);
            $residencias[] = $residencia;
        }

        $pisos = [];
        for ($i = 1; $i <= 3; $i++) {
            $piso = new Piso();
            $piso->setPiPosicion('Posición '.$i)
                ->setPiCuarto('Cuarto '.$i)
                ->setPiZona('Zona '.$i)
                ->setResidenciaId($residencias[$i-1])
                ->setPiEstado(true);
            $manager->persist($piso);
            $pisos[] = $piso;
        }

        $contratos = [];
        for ($i = 0; $i < 3; $i++) {
            $contrato = new Contrato();
            $contrato->setArrendatarioId($arrendatarios[$i])
                ->setPisoId($pisos[$i])
                ->setCoFechaIngreso(new \DateTime('2023-01-01'))
                ->setCoFechaVencimiento(new \DateTime('2024-01-01'))
                ->setCoAlquilerMensual(1500.00 + $i)
                ->setCoAgua(50.00 + $i)
                ->setCoFechaActual(new \DateTime())
                ->setCoEstado(true);
            $manager->persist($contrato);
            $contratos[] = $contrato;
        }

        $medidores = [];
        for ($i = 1; $i <= 3; $i++) {
            $medidor = new Medidor();
            $medidor->setMelCodigo('COD-'.$i)
                ->setMelTipo('Eléctrico')
                ->setMelMarca('Marca '.$i)
                ->setMelAño('202'.rand(0, 9))
                ->setMelFechaCompra(new \DateTime('2022-01-'.rand(1, 28)))
                ->setMelFechaInstalacion(new \DateTime('2022-02-'.rand(1, 28)))
                ->setMelFechaDesinstalacion(null)
                ->setMelEstado(true);
            $manager->persist($medidor);
            $medidores[] = $medidor;
        }

        $lecturas = [];
        for ($i = 1; $i <= 3; $i++) {
            $lectura = new Lectura();
            $lectura->setLelDato(rand(100, 500))
                ->setLelFecha(new \DateTime('2023-01-'.$i))
                ->setLelTipo('Tipo '.$i)
                ->setLelEstado(true)
                ->setMedidorId($medidores[$i-1]);
            $manager->persist($lectura);
            $lecturas[] = $lectura;
        }

        $conceptosPago = [];
        for ($i = 1; $i <= 3; $i++) {
            $conceptoPago = new ConceptoPago();
            $conceptoPago->setCopNombre('Concepto '.$i)
                ->setCopDescripcion('Descripción del concepto '.$i)
                ->setCopPrecio(100.00 + ($i * 10))
                ->setCopEstado(true);
            $manager->persist($conceptoPago);
            $conceptosPago[] = $conceptoPago;
        }

        $recibos = [];
        for ($i = 1; $i <= 3; $i++) {
            $recibo = new Recibo();
            $recibo->setReCodigo('REC-'.$i)
                ->setReFechaEmision(new \DateTime('2023-02-'.$i))
                ->setContratoId($contratos[$i-1])
                ->setReEstado(true)
                ->setRePagoTotal(rand(300, 600));
            $manager->persist($recibo);
            $recibos[] = $recibo;
        }

        for ($i = 1; $i <= 3; $i++) {
            $detalleConsumoLuz = new DetalleConsumoLuz();
            $detalleConsumoLuz->setDclConsumo(rand(50, 150))
                ->setDclTipo('Consumo '.$i)
                ->setDclSubtotal(rand(100, 300))
                ->setDclEstado(true)
                ->setLecturaAnteriorId($lecturas[$i-1])
                ->setLecturaActualId($lecturas[$i-1])
                ->setReciboId($recibos[$i-1]);
            $manager->persist($detalleConsumoLuz);
        }

        for ($i = 1; $i <= 3; $i++) {
            $reciboConceptoPago = new ReciboConceptoPago();
            $reciboConceptoPago->setRcpFechaDigitacion(new \DateTime('2023-02-'.$i))
                ->setReciboId($recibos[$i-1])
                ->setConceptoPagoId($conceptosPago[$i-1]);
            $manager->persist($reciboConceptoPago);
        }
        $manager->flush();
    }
}
