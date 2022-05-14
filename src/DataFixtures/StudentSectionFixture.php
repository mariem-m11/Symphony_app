<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use App\Entity\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentSectionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i =0 ; $i<20; $i++){
            $etd = new Etudiant();
            $sect = new Section();
            $etd->setNom("name".$i);
            $etd->setPrenom("firstname".$i);
            $sect->setDesignation('section '.$i);
            $etd->setSection($sect);
            $manager->persist($etd);
            $manager->persist($sect);

        }
        for ($i =0 ; $i<10; $i++){
            $etd = new Etudiant();
            $etd->setNom("name".$i);
            $etd->setPrenom("firstname".$i);
            $manager->persist($etd);

        }
        $manager->flush();
    }
}
