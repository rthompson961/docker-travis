<?php

namespace App\DataFixtures;

use App\Entity\Fruit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FruitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = ['orange', 'apple', 'banana', 'strawberry'];

        foreach ($data as $item) {
            $fruit = new Fruit();
            $fruit->setName($item);
            $fruit->setState('ready');
            $manager->persist($fruit);
        }
        $manager->flush();
    }
}
