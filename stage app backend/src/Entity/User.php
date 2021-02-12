<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(attributes={
 *     "fetchEager": false,
 *      "normalization_context"={"groups"={"user_group"}}
 *     })
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 */
class User implements UserInterface, \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ficheArrivage_group","commentaire_group","user_group","article_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"user_group"})
     */
    private $cin;

    /**
     * @ORM\Column(type="string")
     * @Groups({"ficheArrivage_group","commentaire_group","user_group","article_group"})
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_group"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @Groups({"ficheArrivage_group","commentaire_group","user_group","article_group"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_group"})
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_group"})
     */
    private $niveauScolaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceNiveau", inversedBy="users")
     * @Groups({"user_group"})
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user_group"})
     */
    public $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user", cascade={"persist", "remove"})
     */
    public $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commantaire", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user_group"})
     */
    public $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageAchat", mappedBy="user", cascade={"persist", "remove"})
     */
    public $ficheArrivageAchats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageTransitaire", mappedBy="user", cascade={"persist", "remove"})
     */
    public $ficheArrivageTransitaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dume", mappedBy="user", cascade={"persist", "remove"})
     */
    public $dumes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageFinance", mappedBy="user", cascade={"persist", "remove"})
     */
    public $ficheArrivageFinances;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheArrivageMagasinier", mappedBy="user", cascade={"persist", "remove"})
     */
    public $ficheArrivageMagasiniers;


    public function __construct($email)
    {
        $this->email = $email;
        $this->images = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->ficheArrivageAchats = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $niveauScolaire
     */
    public function setNiveauScolaire($niveauScolaire): void
    {
        $this->niveauScolaire = $niveauScolaire;
    }

    /**
     * @return mixed
     */
    public function getNiveauScolaire()
    {
        return $this->niveauScolaire;
    }

    /**
     * @param mixed $cin
     */
    public function setCin($cin): void
    {
        $this->cin = $cin;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        if ($this->getRole() == null) {
            return array();
        }
        return array("service" => $this->getRole()->getService(),
            "niveau" => $this->getRole()->getNiveau());
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }


    public function getRole(): ?ServiceNiveau
    {
        return $this->role;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {

    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @param ArrayCollection $images
     */
    public function setImages(ArrayCollection $images): void
    {
        $this->images = $images;
    }

    public function addImage(Image $image): void
    {
        $image->getUser($this);
        $this->images->add($this->images);
    }

    public function removeImage(Image $image): void
    {
        $image->setUser(null);
        $this->images->removeElement($image);
    }

    /**
     * @param ArrayCollection $notifications
     */
    public function setNotifications(ArrayCollection $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function addNotification(Notification $notification): void
    {
        $this->notifications->getUser($this);
        $this->notifications->add($notification);
    }

    public function removeNotification(Notification $notification): void
    {
        $notification->setUser(null);
        $this->notifications->removeElement($notification);
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
     * @param ArrayCollection $ficheArrivageAchats
     */
    public function setFicheArrivageAchats(ArrayCollection $ficheArrivageAchats): void
    {
        $this->ficheArrivageAchats = $ficheArrivageAchats;
    }

    public function addFicheArrivageAchat(FicheArrivageAchat $ficheArrivageAchat): void
    {
        $this->ficheArrivageAchats->getUser($this);
        $this->ficheArrivageAchats->add($ficheArrivageAchat);
    }

    public function removeFicheArrivageAchat(FicheArrivageAchat $ficheArrivageAchat): void
    {
        $this->ficheArrivageAchats->setUser(null);
        $this->ficheArrivageAchats->removeElement($ficheArrivageAchat);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "fullName" => $this->getFullName(),
            "email" => $this->getEmail(),
            "cin" => $this->getCin(),
            "password" => $this->getPassword(),
            "tel" => $this->getTel(),
            "niveauScolaire" => $this->getNiveauScolaire(),
            "roles" => $this->getRoles()
        ];
    }

    public function getUserId()
    {
        return $this->getId();
    }
}
