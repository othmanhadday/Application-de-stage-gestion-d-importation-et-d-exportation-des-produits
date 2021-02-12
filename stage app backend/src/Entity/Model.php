<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"model_group"}}
 *     })
 * @ORM\Entity(repositoryClass=ModelRepository::class)
 */
class Model
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","artiFiche_group","article_group","model_group","nomenclature_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ficheArrivage_group","artiFiche_group","article_group","model_group","nomenclature_group"})
     */
    private $refMachine;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ficheArrivage_group","artiFiche_group","article_group","model_group","nomenclature_group"})
     */
    private $nomMachine;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"ficheArrivage_group","artiFiche_group","article_group","model_group","nomenclature_group"})
     */
    private $quantiteTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"ficheArrivage_group","artiFiche_group","article_group","model_group","nomenclature_group"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group","nomenclature_group"})
     */
    private $activteArticleSerAchat1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group","nomenclature_group"})
     */
    private $activteArticleSerAchat0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group","nomenclature_group"})
     */
    private $activteArticleSerTransitaire0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group","nomenclature_group"})
     */
    private $activteArticleSerInfo1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group","nomenclature_group"})
     */
    private $activteArticleSerInfo0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="models")
     * @Groups({"article_group","model_group"})
     */
    private $article;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Nomenclature", mappedBy="models")
     * @Groups({"ficheArrivage_group"})
     * @var Collection
     */
    public $nomenclatures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleFicheArrivageAchat", mappedBy="model", cascade={"persist", "remove"})
     */
    public $articleFicheArrivageAchats;

    public function __construct()
    {
        $this->nomenclatures = new ArrayCollection();
        $this->articleFicheArrivageAchats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefMachine(): ?string
    {
        return $this->refMachine;
    }

    public function setRefMachine(string $refMachine): self
    {
        $this->refMachine = $refMachine;

        return $this;
    }

    public function getNomMachine(): ?string
    {
        return $this->nomMachine;
    }

    public function setNomMachine(string $nomMachine): self
    {
        $this->nomMachine = $nomMachine;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantiteTotal()
    {
        return $this->quantiteTotal;
    }

    /**
     * @param mixed $quantiteTotal
     */
    public function setQuantiteTotal($quantiteTotal): void
    {
        $this->quantiteTotal = $quantiteTotal;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getActivteArticleSerAchat1(): ?bool
    {
        return $this->activteArticleSerAchat1;
    }

    public function setActivteArticleSerAchat1(?bool $activteArticleSerAchat1): self
    {
        $this->activteArticleSerAchat1 = $activteArticleSerAchat1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivteArticleSerAchat0()
    {
        return $this->activteArticleSerAchat0;
    }

    /**
     * @param mixed $activteArticleSerAchat0
     */
    public function setActivteArticleSerAchat0($activteArticleSerAchat0): void
    {
        $this->activteArticleSerAchat0 = $activteArticleSerAchat0;
    }

    /**
     * @return mixed
     */
    public function getActivteArticleSerTransitaire0()
    {
        return $this->activteArticleSerTransitaire0;
    }

    /**
     * @param mixed $activteArticleSerTransitaire0
     */
    public function setActivteArticleSerTransitaire0($activteArticleSerTransitaire0): void
    {
        $this->activteArticleSerTransitaire0 = $activteArticleSerTransitaire0;
    }

    /**
     * @return mixed
     */
    public function getActivteArticleSerInfo1()
    {
        return $this->activteArticleSerInfo1;
    }

    /**
     * @param mixed $activteArticleSerInfo1
     */
    public function setActivteArticleSerInfo1($activteArticleSerInfo1): void
    {
        $this->activteArticleSerInfo1 = $activteArticleSerInfo1;
    }

    /**
     * @return mixed
     */
    public function getActivteArticleSerInfo0()
    {
        return $this->activteArticleSerInfo0;
    }

    /**
     * @param mixed $activteArticleSerInfo0
     */
    public function setActivteArticleSerInfo0($activteArticleSerInfo0): void
    {
        $this->activteArticleSerInfo0 = $activteArticleSerInfo0;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article): void
    {
        $this->article = $article;
    }

    /**
     * @return Collection
     */
    public function getNomenclatures(): Collection
    {
        return $this->nomenclatures;
    }

    public function addNomenclature(Nomenclature $nomenclature): void
    {
        if ($this->nomenclatures->contains($nomenclature)) {
            return;
        }
        $this->nomenclatures->add($nomenclature);
        $nomenclature->addModel($this);
    }

    public function removeNomenclature(Nomenclature $nomenclature): void
    {
        if (!$this->nomenclatures->contains($nomenclature)) {
            return;
        }
        $this->nomenclatures->removeElement($nomenclature);
        $nomenclature->removeModel($this);
    }
}
