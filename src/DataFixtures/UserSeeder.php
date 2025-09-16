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

        // Liste des bénévoles à créer
        $volunteers = [
            [
                'firstname' => 'Léonie',
                'lastname'  => 'Miège',
                'email'     => 'leoniemiege@gmail.com',
                'password'  => 'leonie123',
            ],
            [
                'firstname' => 'Carine',
                'lastname'  => 'Randri',
                'email'     => 'carine.randri@example.com',
                'password'  => 'carine123',
            ],
            [
                'firstname' => 'Theo',
                'lastname'  => 'Paolo',
                'email'     => 'theo.paolo@example.com',
                'password'  => 'theo123',
            ],
        ];

        foreach ($volunteers as $data) {
            $existingVolunteer = $manager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if (!$existingVolunteer) {
                $volunteer = new User();
                $volunteer->setFirstname($data['firstname'])
                    ->setLastname($data['lastname'])
                    ->setEmail($data['email'])
                    ->setRole('Volunteer')
                    ->setPassword($this->passwordHasher->hashPassword($volunteer, $data['password']))
                    ->setIsActive(true);

                $manager->persist($volunteer);
            }
        }

        $manager->flush();
    }
}
