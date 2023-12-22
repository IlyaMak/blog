<?php

declare(strict_types=1);

namespace App\Entity;

class Tag
{
    public function __construct(
        private ?int $id,
        private string $name,
        private bool $isVisible,
        private ?int $parentTagId
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    public function getParentTagId(): ?int
    {
        return $this->parentTagId;
    }

    public function setParentTagId(int $parentTagId): void
    {
        $this->parentTagId = $parentTagId;
    }
}
