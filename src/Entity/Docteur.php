<?php

namespace App\Entity;

use App\Repository\DocteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Consultation;
use App\Entity\Consultation\Doctrine\ORM\Mapping;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 *
 * @ORM\Entity(repositoryClass=DocteurRepository::class)
 */
class Docteur
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="docteurs")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Consultation", mappedBy="docteur")
     */
    private $consultations;

    /**
     * @ORM\ManyToMany(targetEntity="Questions", inversedBy="docteurs")
     * @ORM\JoinTable(name="docteurs_groups")
     */
    private $questions;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rpps;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motDePasse;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRpps(): ?string
    {
        return $this->rpps;
    }

    public function setRpps(string $rpps): self
    {
        $this->rpps = $rpps;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

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

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setDocteur($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getDocteur() === $this) {
                $consultation->setDocteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questions $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
        }

        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        $this->questions->removeElement($question);

        return $this;
    }
}
