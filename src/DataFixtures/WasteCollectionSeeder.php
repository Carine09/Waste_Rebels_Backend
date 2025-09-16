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
        // Get Volunteers
        $leonie = $manager->getRepository(User::class)->findOneBy(['email' => 'leoniemiege@gmail.com']);
        $carine = $manager->getRepository(User::class)->findOneBy(['email' => 'carine.randri@example.com']);
        $theo   = $manager->getRepository(User::class)->findOneBy(['email' => 'theo.paolo@example.com']);

        if (!$leonie || !$carine || !$theo) {
            throw new \RuntimeException("One or more Volunteers were not found. Run UserSeeder first.");
        }

        // Get Locations (from LocationSeeder)
        $lyon      = $manager->getRepository(Location::class)->findOneBy(['city' => 'Lyon']);
        $marseille = $manager->getRepository(Location::class)->findOneBy(['city' => 'Marseille']);
        $paris     = $manager->getRepository(Location::class)->findOneBy(['city' => 'Paris']);

        if (!$lyon || !$marseille || !$paris) {
            throw new \RuntimeException("Cities Lyon, Marseille and Paris must exist (via LocationSeeder).");
        }

        // Get Waste Types already in database (via WasteTypeSeeder)
        $wasteTypes = $manager->getRepository(WasteType::class)->findAll();
        if (count($wasteTypes) < 6) {
            throw new \RuntimeException("All WasteTypes must be seeded first (via WasteTypeSeeder).");
        }

        // Associate Volunteers and Locations
        $userLocations = [
            [$leonie, $lyon],
            [$theo, $lyon],
            [$carine, $marseille],
        ];

        // Create 3 WasteCollections per Volunteer
        foreach ($userLocations as [$user, $location]) {
            for ($i = 1; $i <= 3; $i++) {
                $wasteCollection = new WasteCollection();
                $wasteCollection->setUser($user);
                $wasteCollection->setLocation($location);

                // Add 2 to 6 random WasteItems with unique WasteTypes
                $itemsCount = rand(2, 6);
                
                // Shuffle the WasteTypes array and take the first elements
                $shuffledWasteTypes = $wasteTypes;
                shuffle($shuffledWasteTypes);
                
                // Take only the desired number of items (without exceeding available types)
                $selectedWasteTypes = array_slice($shuffledWasteTypes, 0, min($itemsCount, count($wasteTypes)));
                
                foreach ($selectedWasteTypes as $wasteType) {
                    $wasteItem = new WasteItem();
                    $wasteItem->setWasteCollection($wasteCollection);
                    $wasteItem->setWasteType($wasteType);

                    // Random whole number amount between 1 and 40 kg
                    $wasteItem->setAmount(rand(1, 40));

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