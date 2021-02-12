<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConteneurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ConteneurRepository::class)
 */
class Conteneur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $numConteneur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleFicheArrivageAchat", mappedBy="conteneur", cascade={"persist", "remove"})
     */
    public $articleFicheArrivageAchats;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNumConteneur()
    {
        return $this->numConteneur;
    }

    /**
     * @param mixed $numConteneur
     */
    public function setNumConteneur($numConteneur): void
    {
        $this->numConteneur = $numConteneur;
    }
}
