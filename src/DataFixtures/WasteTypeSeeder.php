<?php

namespace App\DataFixtures;

use App\Entity\WasteType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WasteTypeSeeder extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            'cigarettes',
            'plastic',
            'glass',
            'electronic_waste',
            'metal_waste',
            'others',
        ];

        foreach ($types as $value) {
            $existing = $manager->getRepository(WasteType::class)->findOneBy(['value' => $value]);
            if (!$existing) {
                $wasteType = new WasteType();
                $wasteType->setValue($value);
                $manager->persist($wasteType);
            }
        }

        $manager->flush();
    }
}
