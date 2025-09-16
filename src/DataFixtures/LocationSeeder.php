<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationSeeder extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cities = ["Lyon", "Marseille", "Paris"];

        foreach ($cities as $cityName) {
            $existing = $manager->getRepository(Location::class)->findOneBy(['city' => $cityName]);
            if (!$existing) {
                $location = new Location();
                $location->setCity($cityName);
                $manager->persist($location);
            }
        }

        $manager->flush();
    }
}
