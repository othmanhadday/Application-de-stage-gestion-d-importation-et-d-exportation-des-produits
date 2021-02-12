<?php

namespace App\DataFixtures;

use App\Entity\Depot;
use App\Entity\Niveau;
use App\Entity\Service;
use App\Repository\NiveauRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{
    const niveaux = array(
        "niveau 0",
        "niveau 1",
        "niveau 2",
        "manutentionnaire",
    );

    const services = array(
        "Service Informatique",
        "Service Magasinier",
        "Service Finance",
        "Service Transitaire",
        "Service Achat",
        "Service Logistique",
        "Service Commercial",
        "Service Ressource humaine",
        "Service Administration",
        "Service Après vente"
    );

    const depot = array(
        'Agadir',
        'Casablanca',
        'Tanger'
    );

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < count(self::niveaux); $i++) {
            $niveau = new Niveau();
            $niveau->setName(self::niveaux[$i]);
            $manager->persist($niveau);
        }
        for ($i = 0; $i < count(self::services); $i++) {
            $service = new Service();
            $service->setName(self::services[$i]);
            $manager->persist($service);
        }

        for ($i = 0; $i < count(self::depot); $i++) {
            $depot = new Depot();
            $depot->setName(self::depot[$i]);
            $manager->persist($depot);
        }

        $manager->flush();

    }
}
