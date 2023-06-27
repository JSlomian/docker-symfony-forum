<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {

    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setIsVerified(true);
        $admin->setEmail('admin@forum.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'admin'));
        $manager->persist($admin);

        $user = new User();
        $user->setIsVerified(true);
        $user->setEmail('user@forum.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user,'user'));
        $manager->persist($user);

        $manager->flush();
    }
}
