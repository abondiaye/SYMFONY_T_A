<?php

namespace App\Search;

class SearchArticle
{
    public function __construct(
        private ?string $title = null,
        private ?array $tags = [],
        private ?array $authors = [],
        private int $page = 1
    ) {
    }

    /**
     *
     * @return ?array
     */
    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    /**
     * Permet de définir les auteurs.
     *      
     * @param ?array $authors
     */
    public function setAuthors(?array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Permet de savoir si la recherche est vide.
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Permet de définir le titre.
     *
     * @param ?string $title
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Permet de savoir si la recherche est vide.
     *
     * @return ?array
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * Permet de définir les tags.
     *
     * @param ?array $tags
     */
    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * permet de récupérer la page.
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * permet de définir la page.
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }
}
