<?php

namespace Entity;

class Tag
{
    private string $id;
    private string $name;
    private bool $isVisible;
    private ?int $parentTag;

    public function __construct(
        string $name,
        string $isVisible,
        ?int $parentTag
    ) {
        $this->name = $name;
        $this->isVisible = $isVisible;
        $this->parentTag = $parentTag;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }

    public function getParentTag(): ?int
    {
        return $this->parentTag;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setIsVisible(string $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    public function setPaarentTag(string $parentTag): void
    {
        $this->parentTag = $parentTag;
    }
}
