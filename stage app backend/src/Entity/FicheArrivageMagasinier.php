<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FicheArrivageMagasinierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=FicheArrivageMagasinierRepository::class)
 */
class FicheArrivageMagasinier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $dateArriveDepot;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierManitentionnaire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierMagasinier0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierMagasinier1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierMagasinier2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleFicheArrivageAchat", inversedBy="ficheArrivageMagasiniers")
     * @Groups({"ficheArrivage_group"})
     */
    private $articleFicheArrivage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageMagasinierManutentionnaire", mappedBy="ficheArrivageMagasinier", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $ficheArrivageMagasinierManutentionnaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageOutsideConteneur", mappedBy="articleFicheArrivageMagasinier", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $imagesOutSide;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageInsideConteneur", mappedBy="articleFicheArrivageMagasinier", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $imagesInside;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ficheArrivageMagasiniers")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $dateArriveDepot
     */
    public function setDateArriveDepot($dateArriveDepot): void
    {
        $this->dateArriveDepot = $dateArriveDepot;
    }

    /**
     * @return mixed
     */
    public function getDateArriveDepot()
    {
        return $this->dateArriveDepot;
    }

    /**
     * @param mixed $verifierMagasinier0
     */
    public function setVerifierMagasinier0($verifierMagasinier0): void
    {
        $this->verifierMagasinier0 = $verifierMagasinier0;
    }

    /**
     * @return mixed
     */
    public function getVerifierMagasinier0()
    {
        return $this->verifierMagasinier0;
    }

    /**
     * @param mixed $verifierMagasinier1
     */
    public function setVerifierMagasinier1($verifierMagasinier1): void
    {
        $this->verifierMagasinier1 = $verifierMagasinier1;
    }

    /**
     * @return mixed
     */
    public function getVerifierMagasinier1()
    {
        return $this->verifierMagasinier1;
    }

    /**
     * @param mixed $verifierManitentionnaire
     */
    public function setVerifierManitentionnaire($verifierManitentionnaire): void
    {
        $this->verifierManitentionnaire = $verifierManitentionnaire;
    }

    /**
     * @return mixed
     */
    public function getVerifierManitentionnaire()
    {
        return $this->verifierManitentionnaire;
    }

    /**
     * @param mixed $verifierMagasinier2
     */
    public function setVerifierMagasinier2($verifierMagasinier2): void
    {
        $this->verifierMagasinier2 = $verifierMagasinier2;
    }

    /**
     * @return mixed
     */
    public function getVerifierMagasinier2()
    {
        return $this->verifierMagasinier2;
    }

    /**
     * @return mixed
     */
    public function getArticleFicheArrivage()
    {
        return $this->articleFicheArrivage;
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
}
