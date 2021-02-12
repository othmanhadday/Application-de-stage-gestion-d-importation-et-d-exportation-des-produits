<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlertFcheArrivageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"AlertficheArrivage_group"}}
 *     })
 * @ORM\Entity(repositoryClass=AlertFcheArrivageRepository::class)
 */
class AlertFcheArrivage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"AlertficheArrivage_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"AlertficheArrivage_group"})
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Groups({"AlertficheArrivage_group"})
     */
    private $showIn;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheArrivageAchat", inversedBy="Alerts")
     * @Groups({"AlertficheArrivage_group"})
     */
    private $ficheArrivageAchat;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
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
    public function getFicheArrivageAchat()
    {
        return $this->ficheArrivageAchat;
    }

    /**
     * @param mixed $ficheArrivageAchat
     */
    public function setFicheArrivageAchat($ficheArrivageAchat): void
    {
        $this->ficheArrivageAchat = $ficheArrivageAchat;
    }

    /**
     * @return mixed
     */
    public function getShowIn()
    {
        return $this->showIn;
    }

    /**
     * @param mixed $showIn
     */
    public function setShowIn($showIn): void
    {
        $this->showIn = $showIn;
    }
}
