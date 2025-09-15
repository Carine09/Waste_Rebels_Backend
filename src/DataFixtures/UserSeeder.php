<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserSeeder extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Vérifie si admin existe déjà
        $existingAdmin = $manager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        if (!$existingAdmin) {
            $admin = new User();
            $admin->setFirstname('Admin')
                ->setLastname('User')
                ->setEmail('admin@example.com')
                ->setRole('Admin')
                ->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'))
                ->setIsActive(true);

            $manager->persist($admin);
        }

        // Vérifie si volunteer existe déjà
        $existingVolunteer = $manager->getRepository(User::class)->findOneBy(['email' => 'volunteer@example.com']);
        if (!$existingVolunteer) {
            $volunteer = new User();
            $volunteer->setFirstname('John')
                ->setLastname('Doe')
                ->setEmail('volunteer@example.com')
                ->setRole('Volunteer')
                ->setPassword($this->passwordHasher->hashPassword($volunteer, 'volunteer123'))
                ->setIsActive(true);

            $manager->persist($volunteer);
        }

        $manager->flush();
    }
}
