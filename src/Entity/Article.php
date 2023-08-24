<?php

namespace App\Entity;

use App\Entity\Categories;
use App\Entity\Beneficiaire;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'articles')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Beneficiaire::class, inversedBy: 'articles')]
    private Collection $beneficiaires;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'articles')]
    private Collection $users;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->beneficiaires = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addArticle($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Beneficiaire>
     */
    public function getBeneficiaires(): Collection
    {
        return $this->beneficiaires;
    }

    public function addBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if (!$this->beneficiaires->contains($beneficiaire)) {
            $this->beneficiaires->add($beneficiaire);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): static
    {
        $this->beneficiaires->removeElement($beneficiaire);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }
}