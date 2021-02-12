<?php

namespace App\Entity;

use App\Repository\ServiceNiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=ServiceNiveauRepository::class)
 */
class ServiceNiveau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_group"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="serviceNiveaux")
     * @Groups({"user_group"})
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Niveau", inversedBy="niveauServices")
     * @Groups({"user_group"})
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role", cascade={"remove"}, fetch="EAGER")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;
        return $this;
    }


    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }


    /**
     * @param ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users): void
    {
        $this->users = $users;
    }


    public function addUser(User $user): void
    {
        $user->setRole($this);
        $this->users->add($user);
    }

    public function removeUser(User $user): void
    {
        $user->setRole(null);
        $this->users->removeElement($user);
    }
}
