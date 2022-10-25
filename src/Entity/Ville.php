<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
// #[ApiResource(collectionOperations: ['get' => ['normalization_context' => ['groups' => 'ville:list']]],
// itemOperations: ['get' => ['normalization_context' => ['groups' => 'ville:item']]],
// paginationEnabled: false,)]
#[ApiResource()]

class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ville:list', 'ville:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['ville:list', 'ville:item'])]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(['ville:list', 'ville:item'])]
    private ?int $codePostal = null;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Lieu::class)]
    private Collection $lieux;

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

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }


    /**
     * @return Collection<int, Lieu>
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieu): self
    {
        if (!$this->lieux->contains($lieu)) {
            $this->lieux->add($lieu);
            $lieu->setVille($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): self
    {
        if ($this->lieux->removeElement($lieu)) {
            // set the owning side to null (unless already changed)
            if ($lieu->getVille() === $this) {
                $lieu->setVille(null);
            }
        }

        return $this;
    }
}
