<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Table(name="consultation")
 * @ORM\Entity(repositoryClass=ConsultationRepository::class)
 */
class Consultation
{

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="consultations")
     */
    private $users;

    /**
     * One consultation has many ordonnances. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Ordonnance", mappedBy="consultation")
     */
    private $Ordonnances;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"consultation"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"consultation"})
     */
    private $diagnostic;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"consultation"})
     */
    private $note;

    public function __construct()
    {
        $this->Ordonnances = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection<int, Ordonnance>
     */
    public function getOrdonnances(): Collection
    {
        return $this->Ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): self
    {
        if (!$this->Ordonnances->contains($ordonnance)) {
            $this->Ordonnances[] = $ordonnance;
            $ordonnance->setConsultation($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): self
    {
        if ($this->Ordonnances->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getConsultation() === $this) {
                $ordonnance->setConsultation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
