<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"article_group"}}
 *     })
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"article_group","model_group"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article_group","model_group"})
     */

    private $refArticle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article_group","model_group"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"article_group","model_group"})
     */
    private $quantiteTotal;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group"})
     */
    private $activteArticleSerAchat1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group"})
     */
    private $activteArticleSerAchat0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group"})
     */
    private $activteArticleSerTransitaire0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group"})
     */
    private $activteArticleSerInfo1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"article_group","model_group"})
     */
    private $activteArticleSerInfo0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="articles")
     * @Groups({"article_group"})
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Model", mappedBy="article", cascade={"persist", "remove"})
     * @Groups({"article_group"})
     */
    public $models;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commantaire", mappedBy="article", cascade={"persist", "remove"})
     * @Groups({"article_group"})
     */
    public $commentaires;

    public function __construct()
    {
        $this->models = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefArticle(): ?string
    {
        return $this->refArticle;
    }

    public function setRefArticle(string $refArticle): self
    {
        $this->refArticle = $refArticle;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantiteTotal(): ?int
    {
        return $this->quantiteTotal;
    }

    public function setQuantiteTotal(int $quantiteTotal): self
    {
        $this->quantiteTotal = $quantiteTotal;

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
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie): void
    {
        $this->categorie = $categorie;
    }

    /**
     * @param ArrayCollection $models
     */
    public function setModels(ArrayCollection $models): void
    {
        $this->models = $models;
    }

    public function addModel(Model $model): void
    {
        $model->getArticle($this);
        $this->models->add($model);
    }

    public function removeModel(Model $model): void
    {
        $model->setArticle(null);
        $this->models->removeElement($model);
    }

    /**
     * @param ArrayCollection $commentaires
     */
    public function setCommentaires(ArrayCollection $commentaires): void
    {
        $this->commentaires = $commentaires;
    }

    public function addCommentaire(Commantaire $commantaire): void
    {
        $commantaire->getUser($this);
        $this->commentaires->add($commantaire);
    }

    public function removeCommentaire(Commantaire $commantaire): void
    {
        $commantaire->setUser(null);
        $this->commentaires->removeElement($commantaire);
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "refArticle" => $this->getRefArticle(),
            "quantiteTotal" => $this->getQuantiteTotal(),
            "categorie" => $this->getCategorie()
        ];
    }
}
