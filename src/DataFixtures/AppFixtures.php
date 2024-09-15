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
        for ($i = 1; $i <= 20; $i++) {
            $arrendatario = new Arrendatario();
            $arrendatario->setAoNombres('Nombre '.$i)
                ->setAoApellidos('Apellido '.$i)
                ->setAoTelefono('98765432'.$i)
                ->setAoTipo('Tipo '.$i)
                ->setAoCedulaIdentidad('7654321'.$i)
                ->setAoFechaNacimiento(new \DateTime('1980-05-10'))
                ->setAoFotoDni(null)
                ->setAoFoto(null)
                ->setAoEstado(true);
            $manager->persist($arrendatario);
            $arrendatarios[] = $arrendatario;
        }

        $residencias = [];
        for ($i = 1; $i <= 10; $i++) {
            $residencia = new Residencia();
            $residencia->setUsuario($i % 2 === 0 ? $admin : $user)
                ->setResDireccion('Calle Falsa '.$i);
            $manager->persist($residencia);
            $residencias[] = $residencia;
        }

        $pisos = [];
        foreach ($residencias as $residencia) {
            for ($i = 1; $i <= 3; $i++) {
                $piso = new Piso();
                $piso->setPiPosicion('Posición '.$i)
                    ->setPiCuarto('Cuarto '.$i)
                    ->setPiZona('Zona '.$i)
                    ->setResidenciaId($residencia)
                    ->setPiEstado(true);
                $manager->persist($piso);
                $pisos[] = $piso;
            }
        }

        $contratos = [];
        foreach ($arrendatarios as $i => $arrendatario) {
            if (!isset($pisos[$i])) break;
            $contrato = new Contrato();
            $contrato->setArrendatarioId($arrendatario)
                ->setPisoId($pisos[$i])
                ->setCoFechaIngreso(new \DateTime('2023-01-'.($i+1)))
                ->setCoFechaVencimiento(new \DateTime('2024-01-'.($i+1)))
                ->setCoAlquilerMensual(1500.00 + $i)
                ->setCoAgua(50.00 + $i)
                ->setCoFechaActual(new \DateTime())
                ->setCoEstado(true);
            $manager->persist($contrato);
            $contratos[] = $contrato;
        }

        $medidores = [];
        for ($i = 1; $i <= 20; $i++) {
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
        foreach ($medidores as $medidor) {
            for ($i = 1; $i <= 3; $i++) {
                $lectura = new Lectura();
                $lectura->setLelDato(rand(100, 500))
                    ->setLelFecha(new \DateTime('2023-01-'.$i))
                    ->setLelTipo('Tipo '.$i)
                    ->setLelEstado(true)
                    ->setMedidorId($medidor);
                $manager->persist($lectura);
                $lecturas[] = $lectura;
            }
        }

        $conceptosPago = [];
        for ($i = 1; $i <= 10; $i++) {
            $conceptoPago = new ConceptoPago();
            $conceptoPago->setCopNombre('Concepto '.$i)
                ->setCopDescripcion('Descripción del concepto '.$i)
                ->setCopPrecio(100.00 + ($i * 10))
                ->setCopEstado(true);
            $manager->persist($conceptoPago);
            $conceptosPago[] = $conceptoPago;
        }

        $recibos = [];
        foreach ($contratos as $i => $contrato) {
            for ($j = 1; $j <= 2; $j++) {
                $recibo = new Recibo();
                $recibo->setReCodigo('REC-'.$i.'-'.$j)
                    ->setReFechaEmision(new \DateTime('2023-02-'.$j))
                    ->setContratoId($contrato)
                    ->setReEstado(true)
                    ->setRePagoTotal(rand(300, 600));
                $manager->persist($recibo);
                $recibos[] = $recibo;
            }
        }

        foreach ($lecturas as $i => $lectura) {
            if (!isset($recibos[$i])) break;
            $detalleConsumoLuz = new DetalleConsumoLuz();
            $detalleConsumoLuz->setDclConsumo(rand(50, 150))
                ->setDclTipo('Consumo '.$i)
                ->setDclSubtotal(rand(100, 300))
                ->setDclEstado(true)
                ->setLecturaAnteriorId($lectura)
                ->setLecturaActualId($lectura)
                ->setReciboId($recibos[$i]);
            $manager->persist($detalleConsumoLuz);
        }

        foreach ($recibos as $i => $recibo) {
            if (!isset($conceptosPago[$i % 10])) break;
            $reciboConceptoPago = new ReciboConceptoPago();
            $reciboConceptoPago->setRcpFechaDigitacion(new \DateTime('2023-02-'.($i % 30 + 1)))
                ->setReciboId($recibo)
                ->setConceptoPagoId($conceptosPago[$i % 10]);
            $manager->persist($reciboConceptoPago);
        }
        
        $manager->flush();
    }
}
