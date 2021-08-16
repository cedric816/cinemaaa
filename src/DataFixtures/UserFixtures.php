<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Ced');
        $user->setEmail('ced@mail.com');
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'oui'
        ));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $manager->flush();

        $admin = new User();
        $admin->setName('Admin');
        $admin->setEmail('admin@mail.com');
        $admin->setPassword($this->passwordHasher->hashPassword(
            $admin,
            'oui'
        ));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);
        $manager->flush();
    }
}
