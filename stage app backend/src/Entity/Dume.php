<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DumeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DumeRepository::class)
 */
class Dume
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
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="dumes")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleFicheArrivageAchat", inversedBy="dumes")
     */
    private $articleFicheArrivage;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $articleFicheArrivage
     */
    public function setArticleFicheArrivage($articleFicheArrivage): void
    {
        $this->articleFicheArrivage = $articleFicheArrivage;
    }

    /**
     * @return mixed
     */
    public function getArticleFicheArrivage()
    {
        return $this->articleFicheArrivage;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}
