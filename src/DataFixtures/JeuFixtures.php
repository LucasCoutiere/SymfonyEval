<?php

namespace App\DataFixtures;

use App\Entity\Jeu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JeuFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $jeu = new Jeu();
            $jeu->setName("Name " . $i);
            $jeu->setDescription("Description " . $i);
            $jeu->setYear(mt_rand(2000, 2022));
            $jeu->setRating(mt_rand(70, 100));
            $manager->persist($jeu);
        }

        $manager->flush();
    }
}