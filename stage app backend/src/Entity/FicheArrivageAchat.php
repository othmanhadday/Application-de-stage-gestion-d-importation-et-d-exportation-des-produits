<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestamp;
use App\Repository\FicheArrivageAchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FicheArrivageAchatRepository::class)
 * @ApiResource(
 *     mercure={"private": true},
 *     attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"ficheArrivage_group"}}
 *     })
 */
class FicheArrivageAchat
{
    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","AlertficheArrivage_group","artiFiche_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","AlertficheArrivage_group"})
     */
    private $activteArticleSerAchat1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","AlertficheArrivage_group"})
     */
    private $activteArticleSerAchat0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","AlertficheArrivage_group"})
     */
    private $activteArticleSerTransitaire0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","AlertficheArrivage_group"})
     */
    private $activteArticleSerInfo1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group","AlertficheArrivage_group"})
     */
    private $activteArticleSerInfo0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"ficheArrivage_group"})
     */
    private $finishOperation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ficheArrivageAchats")
     * @Groups({"ficheArrivage_group"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Depot", inversedBy="ficheArrivageAchats")
     * @Groups({"ficheArrivage_group"})
     */
    private $depot;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commantaire", mappedBy="ficheArrivageAchat", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group"})
     */
    public $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleFicheArrivageAchat", mappedBy="ficheArrivageAchat", cascade={"persist", "remove"})
     * @Groups({"ficheArrivage_group"})
     */
    public $articleFicheArrivageAchats;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->articleFicheArrivageAchats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getActivteArticleSerAchat1()
    {
        return $this->activteArticleSerAchat1;
    }

    /**
     * @param mixed $activteArticleSerAchat1
     */
    public function setActivteArticleSerAchat1($activteArticleSerAchat1): void
    {
        $this->activteArticleSerAchat1 = $activteArticleSerAchat1;
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
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * @param mixed $depot
     */
    public function setDepot($depot): void
    {
        $this->depot = $depot;
    }

    /**
     * @param mixed $finishOperation
     */
    public function setFinishOperation($finishOperation): void
    {
        $this->finishOperation = $finishOperation;
    }

    /**
     * @return mixed
     */
    public function getFinishOperation()
    {
        return $this->finishOperation;
    }


}
