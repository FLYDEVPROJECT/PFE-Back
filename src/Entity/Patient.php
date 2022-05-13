<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="patients")
     */
    private $user;

    /**
     * One patient has many questions. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Questions", mappedBy="patient")
     */
    private $questions;

    /**
     * One patient has many consultations. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Consultation", mappedBy="patient")
     */
    private $consultations;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeSecuriteSociale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motDePasse;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeSecuriteSociale(): ?int
    {
        return $this->codeSecuriteSociale;
    }

    public function setCodeSecuriteSociale(int $codeSecuriteSociale): self
    {
        $this->codeSecuriteSociale = $codeSecuriteSociale;

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
            $question->setPatient($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getPatient() === $this) {
                $question->setPatient(null);
            }
        }

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
            $consultation->setPatient($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getPatient() === $this) {
                $consultation->setPatient(null);
            }
        }

        return $this;
    }
}
