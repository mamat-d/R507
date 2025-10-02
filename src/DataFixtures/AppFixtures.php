<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername('Admin')
            ->setPlainPassword('azerty')
            ->setRoles(['ROLE_ADMIN']);
        $user
            ->initPassword($this->hasher)
            ->eraseCredentials();

        $manager->persist($user);
        $manager->flush();
    }
}
