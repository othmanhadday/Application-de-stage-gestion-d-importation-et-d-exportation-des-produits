<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageOutsideConteneurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ImageOutsideConteneurRepository::class)
 */
class ImageOutsideConteneur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group","user_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ficheArrivage_group","artiFiche_group","artiFicheTrans_group","user_group"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleFicheArrivageAchat", inversedBy="imagesOutSide")
     */
    private $articleFicheArrivageAchat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheArrivageTransitaire", inversedBy="imagesOutSide")
     */
    private $articleFicheArrivageTransitaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheArrivageMagasinier", inversedBy="imagesOutSide")
     */
    private $articleFicheArrivageMagasinier;

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

    /**
     * @param mixed $articleFicheArrivageMagasinier
     */
    public function setArticleFicheArrivageMagasinier($articleFicheArrivageMagasinier): void
    {
        $this->articleFicheArrivageMagasinier = $articleFicheArrivageMagasinier;
    }

    /**
     * @return mixed
     */
    public function getArticleFicheArrivageMagasinier()
    {
        return $this->articleFicheArrivageMagasinier;
    }

    /**
     * @param mixed $articleFicheArrivageTransitaire
     */
    public function setArticleFicheArrivageTransitaire($articleFicheArrivageTransitaire): void
    {
        $this->articleFicheArrivageTransitaire = $articleFicheArrivageTransitaire;
    }

    /**
     * @return mixed
     */
    public function getArticleFicheArrivageTransitaire()
    {
        return $this->articleFicheArrivageTransitaire;
    }

    /**
     * @param mixed $articleFicheArrivageAchat
     */
    public function setArticleFicheArrivageAchat($articleFicheArrivageAchat): void
    {
        $this->articleFicheArrivageAchat = $articleFicheArrivageAchat;
    }

    /**
     * @return mixed
     */
    public function getArticleFicheArrivageAchat()
    {
        return $this->articleFicheArrivageAchat;
    }
}
