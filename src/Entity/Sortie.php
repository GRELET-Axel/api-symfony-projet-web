<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: SortieRepository::class)]
// #[ApiResource(collectionOperations: ['get' => ['normalization_context' => ['groups' => 'sortie:list']]],
//     itemOperations: ['get' => ['normalization_context' => ['groups' => 'sortie:item']]],
//     paginationEnabled: false,)]
// #[ApiResource(normalizationContext: ['jsonld_embed_context' => true])]
#[ApiResource(normalizationContext: ["groups"=>"sortie"])]


class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sortie','user'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sortie','user'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['sortie'])]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sortie'])]
    private ?string $duree = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['sortie'])]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\Column]
    #[Groups(['sortie'])]
    private ?int $nbInscriptionsMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['sortie'])]
    private ?string $infosSortie = null;

    #[ORM\ManyToOne(targetEntity: Etat::class,inversedBy: 'sorties')]
    #[Groups(['sortie'])]
    private ?Etat $etat = null;

    #[ORM\ManyToOne(targetEntity: Campus::class,inversedBy: 'sorties')]
    #[Groups(['sortie'])]
    private ?Campus $campus = null;

    #[ORM\ManyToMany(targetEntity: Participant::class, mappedBy: 'inscrit')]
    #[Groups(['sortie'])]
    private Collection $participants;

    #[ORM\ManyToOne(targetEntity: Participant::class,inversedBy: 'organisateur')]
    #[Groups(['sortie'])]
    private ?Participant $participant = null;

    #[ORM\ManyToOne(targetEntity: Lieu::class,inversedBy: 'sorties')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups(['sortie'])]
    private ?Lieu $lieu = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(?\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(?\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addInscrit($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeInscrit($this);
        }

        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}
