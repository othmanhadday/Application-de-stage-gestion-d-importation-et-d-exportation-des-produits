<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FicheArrivageTransitaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={
 *      "normalization_context"={"groups"={"artiFicheTrans_group"}}
 *     })
 * @ORM\Entity(repositoryClass=FicheArrivageTransitaireRepository::class)
 */
class FicheArrivageTransitaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $inventairePhysique;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $dateSortiePort;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $verifierArticlesServTransitare0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $verifierArticlesServTransitare1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $verifierArticlesServTransitare2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ficheArrivageTransitaires")
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleFicheArrivageAchat", inversedBy="ficheArrivageTransitaires")
     */
    private $articleFicheArrivage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageOutsideConteneur", mappedBy="articleFicheArrivageTransitaire", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    public $imagesOutSide;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageInsideConteneur", mappedBy="articleFicheArrivageTransitaire", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group"})
     */
    public $imagesInside;


    public function getId(): ?int
    {
        return $this->id;
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
     * @param mixed $dateSortiePort
     */
    public function setDateSortiePort($dateSortiePort): void
    {
        $this->dateSortiePort = $dateSortiePort;
    }

    /**
     * @return mixed
     */
    public function getDateSortiePort()
    {
        return $this->dateSortiePort;
    }

    /**
     * @param mixed $inventairePhysique
     */
    public function setInventairePhysique($inventairePhysique): void
    {
        $this->inventairePhysique = $inventairePhysique;
    }

    /**
     * @return mixed
     */
    public function getInventairePhysique()
    {
        return $this->inventairePhysique;
    }

    /**
     * @param mixed $verifierArticlesServTransitare0
     */
    public function setVerifierArticlesServTransitare0($verifierArticlesServTransitare0): void
    {
        $this->verifierArticlesServTransitare0 = $verifierArticlesServTransitare0;
    }

    /**
     * @return mixed
     */
    public function getVerifierArticlesServTransitare0()
    {
        return $this->verifierArticlesServTransitare0;
    }

    /**
     * @param mixed $verifierArticlesServTransitare1
     */
    public function setVerifierArticlesServTransitare1($verifierArticlesServTransitare1): void
    {
        $this->verifierArticlesServTransitare1 = $verifierArticlesServTransitare1;
    }

    /**
     * @return mixed
     */
    public function getVerifierArticlesServTransitare1()
    {
        return $this->verifierArticlesServTransitare1;
    }

    /**
     * @return mixed
     */
    public function getVerifierArticlesServTransitare2()
    {
        return $this->verifierArticlesServTransitare2;
    }

    /**
     * @param mixed $verifierArticlesServTransitare2
     */
    public function setVerifierArticlesServTransitare2($verifierArticlesServTransitare2): void
    {
        $this->verifierArticlesServTransitare2 = $verifierArticlesServTransitare2;
    }


}
