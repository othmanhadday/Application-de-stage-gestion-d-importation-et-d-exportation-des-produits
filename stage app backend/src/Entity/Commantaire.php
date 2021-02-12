<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestamp;
use App\Repository\CommantaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     mercure={"private": true},
 *     attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"commentaire_group"}}
 *     })
 * @ORM\Entity(repositoryClass=CommantaireRepository::class)
 */
class Commantaire implements \JsonSerializable
{
    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","commentaire_group","article_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ficheArrivage_group","commentaire_group","article_group"})
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentaires")
     * @Groups({"ficheArrivage_group","commentaire_group","article_group"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="commentaires")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FicheArrivageAchat", inversedBy="commentaires")
     */
    private $ficheArrivageAchat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'article' => $this->getArticle(),
            'commentaire' => $this->getCommentaire(),
            'user' => $this->getUser(),
            'ficheArrivageAchat' => $this->getFicheArrivageAchat()
        ];
    }
}
