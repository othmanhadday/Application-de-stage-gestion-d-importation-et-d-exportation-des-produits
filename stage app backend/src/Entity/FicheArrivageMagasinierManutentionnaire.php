<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FicheArrivageMagasinierManutentionnaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=FicheArrivageMagasinierManutentionnaireRepository::class)
 */
class FicheArrivageMagasinierManutentionnaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ficheArrivageMagasinierManutentionnaires")
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheArrivageMagasinier", inversedBy="ficheArrivageMagasinierManutentionnaires")
     */
    private $ficheArrivageMagasinier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $quantiteManutentionnaire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierMagasinier2;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
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
    public function getFicheArrivageMagasinier()
    {
        return $this->ficheArrivageMagasinier;
    }

    /**
     * @param mixed $ficheArrivageMagasinier
     */
    public function setFicheArrivageMagasinier($ficheArrivageMagasinier): void
    {
        $this->ficheArrivageMagasinier = $ficheArrivageMagasinier;
    }

    /**
     * @return mixed
     */
    public function getQuantiteManutentionnaire()
    {
        return $this->quantiteManutentionnaire;
    }

    /**
     * @param mixed $quantiteManutentionnaire
     */
    public function setQuantiteManutentionnaire($quantiteManutentionnaire): void
    {
        $this->quantiteManutentionnaire = $quantiteManutentionnaire;
    }

    /**
     * @return mixed
     */
    public function getVerifierMagasinier2()
    {
        return $this->verifierMagasinier2;
    }

    /**
     * @param mixed $verifierMagasinier2
     */
    public function setVerifierMagasinier2($verifierMagasinier2): void
    {
        $this->verifierMagasinier2 = $verifierMagasinier2;
    }
}
