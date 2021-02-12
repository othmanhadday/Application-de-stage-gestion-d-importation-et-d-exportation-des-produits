<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NomenclatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"nomenclature_group"}}
 *     })
 * @ORM\Entity(repositoryClass=NomenclatureRepository::class)
 */
class Nomenclature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"nomenclature_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $refIntern;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $nomFr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $nomAr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $nomEn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $codeShort;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"nomenclature_group"})
     */
    private $codeSage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"nomenclature_group"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Model", inversedBy="nomenclatures")
     * @Groups({"nomenclature_group"})
     * @var Collection
     */
    public $models;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeNomenclature", inversedBy="nomenclatures")
     * @Groups({"nomenclature_group"})
     */
    private $typeNomenclature;

    public function __construct()
    {
        $this->models = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefIntern(): ?string
    {
        return $this->refIntern;
    }

    public function setRefIntern(string $refIntern): self
    {
        $this->refIntern = $refIntern;

        return $this;
    }

    public function getNomFr(): ?string
    {
        return $this->nomFr;
    }

    public function setNomFr(string $nomFr): self
    {
        $this->nomFr = $nomFr;

        return $this;
    }

    public function getNomAr(): ?string
    {
        return $this->nomAr;
    }

    public function setNomAr(string $nomAr): self
    {
        $this->nomAr = $nomAr;

        return $this;
    }

    public function getNomEn(): ?string
    {
        return $this->nomEn;
    }

    public function setNomEn(string $nomEn): self
    {
        $this->nomEn = $nomEn;

        return $this;
    }

    public function getCodeShort(): ?string
    {
        return $this->codeShort;
    }

    public function setCodeShort(string $codeShort): self
    {
        $this->codeShort = $codeShort;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getCodeSage(): ?string
    {
        return $this->codeSage;
    }

    public function setCodeSage(string $codeSage): self
    {
        $this->codeSage = $codeSage;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): void
    {
        $this->models->add($model);
        if ($this->models->contains($model)) {
            return;
        }
        $this->models->add($model);
        $model->addNomenclature($this);
    }

    public function removeModel(Model $model): void
    {
        if (!$this->models->contains($model)) {
            return;
        }
        $this->models->removeElement($model);
        $model->removeNomenclature($this);
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
        $this->commentaires->getUser($this);
        $this->commentaires->add($commantaire);
    }

    public function removeCommentaire(Commantaire $commantaire): void
    {
        $commantaire->setUser(null);
        $this->commentaires->removeElement($commantaire);
    }

    /**
     * @return mixed
     */
    public function getTypeNomenclature()
    {
        return $this->typeNomenclature;
    }

    /**
     * @param mixed $typeNomenclature
     */
    public function setTypeNomenclature($typeNomenclature): void
    {
        $this->typeNomenclature = $typeNomenclature;
    }
}
