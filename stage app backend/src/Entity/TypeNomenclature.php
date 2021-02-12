<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeNomenclatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=TypeNomenclatureRepository::class)
 */
class TypeNomenclature
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
    private $typeName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Nomenclature", mappedBy="typeNomenclature", cascade={"persist", "remove"})
     */
    public $nomenclatures;

    public function getId(): ?int
    {
        return $this->id;
        $this->nomenclatures = new ArrayCollection();
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): self
    {
        $this->typeName = $typeName;

        return $this;
    }

    /**
     * @param mixed $nomenclatures
     */
    public function setNomenclatures($nomenclatures): void
    {
        $this->nomenclatures = $nomenclatures;
    }

    public function addNomenclature(Nomenclature $nomenclature): void
    {
        $nomenclature->getTypeNomenclature($this);
        $this->nomenclatures->add($nomenclature);
    }

    public function removeNomenclature(Nomenclature $nomenclature): void
    {
        $nomenclature->setTypeNomenclature(null);
        $this->nomenclatures->removeElement($this->nomenclatures);
    }
}
