<?php

namespace App\Entity;

use App\Entity\Don;
use App\Entity\Article;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BeneficiaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BeneficiaireRepository::class)]
class Beneficiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ['Orphelin', 'Famille'], message: " Le choix doit concernée: pour un Orphelin/Famille")]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $besoin = null;

    #[ORM\Column]
    private ?float $description = null;

    #[ORM\Column(length: 255)]
    private ?string $source = null;

    #[ORM\OneToMany(mappedBy: 'beneficiaire', targetEntity: Don::class, orphanRemoval: true)]
    private Collection $dons;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'beneficiaires')]
    private Collection $articles;

    public function __construct()
    {
        $this->dons = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBesoin(): ?float
    {
        return $this->besoin;
    }

    public function setBesoin(float $besoin): static
    {
        $this->besoin = $besoin;

        return $this;
    }

    public function getDescription(): ?float
    {
        return $this->description;
    }

    public function setDescription(float $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Don $don): static
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeDon(Don $don): static
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getBeneficiaire() === $this) {
                $don->setBeneficiaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }
}
