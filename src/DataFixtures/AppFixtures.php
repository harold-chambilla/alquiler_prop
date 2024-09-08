<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use App\Entity\Contrato;
use App\Entity\Arrendatario;
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
        $admin->setEmail('admin@ksperu.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, '123456');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $user = new Usuario();
        $user->setEmail('user@ksperu.com');
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, '123456');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $arrendatario1 = new Arrendatario();
        $arrendatario1->setAoNombres('Juan');
        $arrendatario1->setAoApellidos('Perez');
        $arrendatario1->setAoTelefono('999888777');
        $arrendatario1->setAoCedulaIdentidad('12345678');
        $arrendatario1->setAoFechaNacimiento(new \DateTime('1985-07-15'));
        $manager->persist($arrendatario1);

        $arrendatario2 = new Arrendatario();
        $arrendatario2->setAoNombres('Maria');
        $arrendatario2->setAoApellidos('Garcia');
        $arrendatario2->setAoTelefono('999111222');
        $arrendatario2->setAoCedulaIdentidad('87654321');
        $arrendatario2->setAoFechaNacimiento(new \DateTime('1990-03-22'));
        $manager->persist($arrendatario2);

        $contrato1 = new Contrato();
        $contrato1->setArrendatarioId($arrendatario1);
        $contrato1->setUsuarioId($admin);
        $contrato1->setCoEstado(true);
        $manager->persist($contrato1);

        $contrato2 = new Contrato();
        $contrato2->setArrendatarioId($arrendatario2);
        $contrato2->setUsuarioId($user);
        $contrato2->setCoEstado(true);
        $manager->persist($contrato2);

        $manager->flush();
    }
}
