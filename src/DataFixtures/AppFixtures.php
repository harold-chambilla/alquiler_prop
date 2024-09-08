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
        $manager->flush();
    }
}
