<?php

namespace App\DataFixtures;

use App\Entity\WasteCollection;
use App\Entity\User;
use App\Entity\Location;
use App\Entity\WasteType;
use App\Entity\WasteItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface; 

class WasteCollectionSeeder extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupération des Volunteers
        $leonie = $manager->getRepository(User::class)->findOneBy(['email' => 'leoniemiege@gmail.com']);
        $carine = $manager->getRepository(User::class)->findOneBy(['email' => 'carine.randri@example.com']);
        $theo   = $manager->getRepository(User::class)->findOneBy(['email' => 'theo.paolo@example.com']);

        if (!$leonie || !$carine || !$theo) {
            throw new \RuntimeException("Un ou plusieurs Volunteers n'ont pas été trouvés. Lance UserSeeder d'abord.");
        }

        // Récupération des Locations (grâce à LocationSeeder)
        $lyon      = $manager->getRepository(Location::class)->findOneBy(['city' => 'Lyon']);
        $marseille = $manager->getRepository(Location::class)->findOneBy(['city' => 'Marseille']);
        $paris     = $manager->getRepository(Location::class)->findOneBy(['city' => 'Paris']);

        if (!$lyon || !$marseille || !$paris) {
            throw new \RuntimeException("Les villes Lyon, Marseille et Paris doivent exister (via LocationSeeder).");
        }

        // Récupération des Waste Types déjà en base (via WasteTypeSeeder)
        $wasteTypes = $manager->getRepository(WasteType::class)->findAll();
        if (count($wasteTypes) < 6) {
            throw new \RuntimeException("Tous les WasteTypes doivent être seedés avant (via WasteTypeSeeder).");
        }

        // Associer Volunteers et Locations
        $userLocations = [
            [$leonie, $lyon],
            [$theo, $lyon],
            [$carine, $marseille],
        ];

        // Création de 3 WasteCollections par Volunteer
        foreach ($userLocations as [$user, $location]) {
            for ($i = 1; $i <= 3; $i++) {
                $wasteCollection = new WasteCollection();
                $wasteCollection->setUser($user);
                $wasteCollection->setLocation($location);

                // Ajouter 2 à 4 WasteItems aléatoires
                $itemsCount = rand(2, 4);
                for ($j = 0; $j < $itemsCount; $j++) {
                    $wasteItem = new WasteItem();
                    $wasteItem->setWasteCollection($wasteCollection);

                    // Choisir un WasteType existant
                    $wasteType = $wasteTypes[array_rand($wasteTypes)];
                    $wasteItem->setWasteType($wasteType);

                    // Quantité aléatoire entre 0.5 et 10.0 kg
                    $wasteItem->setAmount(mt_rand(5, 100) / 10);

                    $manager->persist($wasteItem);
                }

                $manager->persist($wasteCollection);
            }
        }

        $manager->flush();
    }

     public function getDependencies(): array
    {
        return [
            LocationSeeder::class,
            UserSeeder::class,
            WasteTypeSeeder::class, 
        ];
    }
}
