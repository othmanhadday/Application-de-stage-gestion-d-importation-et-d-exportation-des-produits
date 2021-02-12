<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group"})
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     * @Groups({"ficheArrivage_group"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageAchat", mappedBy="depot", cascade={"persist"})
     */
    public $ficheArrivageAchats;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
