<?php

namespace App\DataFixtures;

use App\Entity\WasteCollection;
use App\Entity\User;
use App\Entity\Location;
use App\Entity\WasteType;
use App\Entity\WasteItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WasteCollectionSeeder extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Catch volunteers
        $leonie = $manager->getRepository(User::class)->findOneBy(['email' => 'leoniemiege@gmail.com']);
        $carine = $manager->getRepository(User::class)->findOneBy(['email' => 'carine.randri@example.com']);
        $theo   = $manager->getRepository(User::class)->findOneBy(['email' => 'theo.paolo@example.com']);

        if (!$leonie || !$carine || !$theo) {
            throw new \RuntimeException("Un ou plusieurs utilisateurs Volunteers n'ont pas été trouvés.");
        }

        // Catch Lyon and Marseille only (exist in the DB)
        $lyon = $manager->getRepository(Location::class)->find(1);
        $marseille = $manager->getRepository(Location::class)->find(2);
        $paris = $manager->getRepository(Location::class)->find(3);

        if (!$lyon || !$marseille || !$paris) {
            throw new \RuntimeException("Les villes Lyon, Marseille et Paris (id: 1, 2, 3) doivent exister en base de données.");
        }

        // Catch Waste Types
        $wasteTypeValues = [
            'cigarettes',
            'plastic',
            'glass',
            'electronic_waste',
            'metal_waste',
            'others',
        ];

        $wasteTypes = [];
        foreach ($wasteTypeValues as $value) {
            $wasteType = $manager->getRepository(WasteType::class)->findOneBy(['value' => $value]);
            if (!$wasteType) {
                $wasteType = new WasteType();
                $wasteType->setValue($value);
                $manager->persist($wasteType);
            }
            $wasteTypes[] = $wasteType;
        }

        // Match Volunteers and locations
        $userLocations = [
            [$leonie, $lyon],
            [$theo, $lyon],
            [$carine, $marseille],
        ];
        // Create 3 WasteCollections per Volunteer with WasteItems
        foreach ($userLocations as [$user, $location]) {
            for ($i = 1; $i <= 3; $i++) {
                $wasteCollection = new WasteCollection();
                $wasteCollection->setUser($user);
                $wasteCollection->setLocation($location);

                // Add 2 to 4 random Waste Items 
                $itemsCount = rand(2, 4);
                for ($j = 0; $j < $itemsCount; $j++) {
                    $wasteItem = new WasteItem();
                    $wasteItem->setWasteCollection($wasteCollection);
                    $wasteItem->setWasteType($wasteTypes[array_rand($wasteTypes)]);
                    $wasteItem->setAmount(mt_rand(5, 100) / 10); // 0.5 à 10.0

                    $manager->persist($wasteItem);
                }

                $manager->persist($wasteCollection);
            }
        }

        $manager->flush();
    }
}
