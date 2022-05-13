<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="questions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialite", inversedBy="questions")
     */
    private $specialites;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSpecialites(): ?Specialite
    {
        return $this->specialites;
    }

    public function setSpecialites(?Specialite $specialites): self
    {
        $this->specialites = $specialites;

        return $this;
    }
}
