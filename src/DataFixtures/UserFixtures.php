<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@quick-couette.fr');
        $admin->setRoles(['ROLE_ADMINISTRATOR']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'adminPassword');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);
        $this->addReference("user_admin", $admin);

        $user = new User();
        $user->setEmail('user@quick-couette.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'userPassword');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $this->addReference("user_user", $user);

        $manager->flush();
    }
}
