<?php

declare(strict_types=1);

namespace App\Entity;

class Post
{
    private int $id;

    public function __construct(
        private string $headline,
        private string $body,
        private ?array $tags,
        private string $publishDate,
        private string $imagePath,
        private bool $isVisible
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function getPublishDate(): string
    {
        return $this->publishDate;
    }

    public function setPublishDate(string $publishDate): void
    {
        $this->publishDate = $publishDate;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }
}
