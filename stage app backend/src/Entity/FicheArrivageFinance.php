<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestamp;
use App\Repository\FicheArrivageFinanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=FicheArrivageFinanceRepository::class)
 */
class FicheArrivageFinance
{

    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $cleExterne;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $cleDouan;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierTemps;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierFinanceNv1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierFinanceNv0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleFicheArrivageAchat", inversedBy="ficheArrivageFinances")
     */
    private $articleFicheArrivage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ficheArrivageFinances")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $commentaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $cleDouan
     */
    public function setCleDouan($cleDouan): void
    {
        $this->cleDouan = $cleDouan;
    }

    /**
     * @return mixed
     */
    public function getCleDouan()
    {
        return $this->cleDouan;
    }

    /**
     * @param mixed $cleExterne
     */
    public function setCleExterne($cleExterne): void
    {
        $this->cleExterne = $cleExterne;
    }

    /**
     * @return mixed
     */
    public function getCleExterne()
    {
        return $this->cleExterne;
    }

    /**
     * @param mixed $verifierFinanceNv0
     */
    public function setVerifierFinanceNv0($verifierFinanceNv0): void
    {
        $this->verifierFinanceNv0 = $verifierFinanceNv0;
    }

    /**
     * @return mixed
     */
    public function getVerifierFinanceNv0()
    {
        return $this->verifierFinanceNv0;
    }

    /**
     * @param mixed $verifierFinanceNv1
     */
    public function setVerifierFinanceNv1($verifierFinanceNv1): void
    {
        $this->verifierFinanceNv1 = $verifierFinanceNv1;
    }

    /**
     * @return mixed
     */
    public function getVerifierFinanceNv1()
    {
        return $this->verifierFinanceNv1;
    }

    /**
     * @param mixed $verifierTemps
     */
    public function setVerifierTemps($verifierTemps): void
    {
        $this->verifierTemps = $verifierTemps;
    }

    /**
     * @return mixed
     */
    public function getVerifierTemps()
    {
        return $this->verifierTemps;
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
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }
}
