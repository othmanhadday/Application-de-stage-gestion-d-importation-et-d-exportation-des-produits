<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleFicheArrivageAchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


//*        collectionOperations={
// *          "get"={
// *               "normalization_context"={"groups"={"articleFicheArrivage"},"enable_max_depth"=true}
// *           },     * @Groups({"articleFicheArrivage"})
// *          "post"
// *       },

/**
 *  @ApiResource(attributes={
 *      "normalization_context"={"groups"={"artiFiche_group"}}
 *     })
 * @ORM\Entity(repositoryClass=ArticleFicheArrivageAchatRepository::class)
 */
class ArticleFicheArrivageAchat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Model", inversedBy="articleFicheArrivageAchats")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheArrivageAchat", inversedBy="articleFicheArrivageAchats")
     * @Groups({"artiFiche_group"})
     */
    private $ficheArrivageAchat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conteneur", inversedBy="articleFicheArrivageAchats")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $conteneur;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $quantiteServAchat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $quantiteServTransitaire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $quantiteServMagasinier;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierArticlesChaqueContenaireTransitaire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $verifierArticlesChaqueContenaireMagasnier;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    private $dateOuvertConteneur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageOutsideConteneur", mappedBy="articleFicheArrivageAchat", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $imagesOutSide;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageInsideConteneur", mappedBy="articleFicheArrivageAchat", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $imagesInside;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageTransitaire", mappedBy="articleFicheArrivage", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $ficheArrivageTransitaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageMagasinier", mappedBy="articleFicheArrivage", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $ficheArrivageMagasiniers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageFinance", mappedBy="articleFicheArrivage", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $ficheArrivageFinances;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dume", mappedBy="articleFicheArrivage", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group","artiFiche_group"})
     */
    public $dumes;

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
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getConteneur()
    {
        return $this->conteneur;
    }

    /**
     * @param mixed $conteneur
     */
    public function setConteneur($conteneur): void
    {
        $this->conteneur = $conteneur;
    }

    /**
     * @return mixed
     */
    public function getQuantiteServAchat()
    {
        return $this->quantiteServAchat;
    }

    /**
     * @param mixed $quantiteServAchat
     */
    public function setQuantiteServAchat($quantiteServAchat): void
    {
        $this->quantiteServAchat = $quantiteServAchat;
    }

    /**
     * @return mixed
     */
    public function getQuantiteServTransitaire()
    {
        return $this->quantiteServTransitaire;
    }

    /**
     * @param mixed $quantiteServTransitaire
     */
    public function setQuantiteServTransitaire($quantiteServTransitaire): void
    {
        $this->quantiteServTransitaire = $quantiteServTransitaire;
    }

    /**
     * @return mixed
     */
    public function getQuantiteServMagasinier()
    {
        return $this->quantiteServMagasinier;
    }

    /**
     * @param mixed $quantiteServMagasinier
     */
    public function setQuantiteServMagasinier($quantiteServMagasinier): void
    {
        $this->quantiteServMagasinier = $quantiteServMagasinier;
    }

    /**
     * @return mixed
     */
    public function getVerifierArticlesChaqueContenaireTransitaire()
    {
        return $this->verifierArticlesChaqueContenaireTransitaire;
    }

    /**
     * @param mixed $verifierArticlesChaqueContenaireTransitaire
     */
    public function setVerifierArticlesChaqueContenaireTransitaire($verifierArticlesChaqueContenaireTransitaire): void
    {
        $this->verifierArticlesChaqueContenaireTransitaire = $verifierArticlesChaqueContenaireTransitaire;
    }

    /**
     * @return mixed
     */
    public function getVerifierArticlesChaqueContenaireMagasnier()
    {
        return $this->verifierArticlesChaqueContenaireMagasnier;
    }

    /**
     * @param mixed $verifierArticlesChaqueContenaireMagasnier
     */
    public function setVerifierArticlesChaqueContenaireMagasnier($verifierArticlesChaqueContenaireMagasnier): void
    {
        $this->verifierArticlesChaqueContenaireMagasnier = $verifierArticlesChaqueContenaireMagasnier;
    }

    /**
     * @return mixed
     */
    public function getDateOuvertConteneur()
    {
        return $this->dateOuvertConteneur;
    }

    /**
     * @param mixed $dateOuvertConteneur
     */
    public function setDateOuvertConteneur($dateOuvertConteneur): void
    {
        $this->dateOuvertConteneur = $dateOuvertConteneur;
    }


}
