<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'restaurant_activity')]
class RestaurantActivity
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private string $type;

    #[ODM\Field(type: 'int', nullable: true)]
    private ?int $menuId = null;

    #[ODM\Field(type: 'int', nullable: true)]
    private ?int $platId = null;

    #[ODM\Field(type: 'int', nullable: true)]
    private ?int $userId = null;

    #[ODM\Field(type: 'date')]
    private \DateTime $date;

    #[ODM\Field(type: 'hash')]
    private array $metadata = [];

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMenuId(): ?int
    {
        return $this->menuId;
    }

    public function setMenuId(?int $menuId): static
    {
        $this->menuId = $menuId;

        return $this;
    }

    public function getPlatId(): ?int
    {
        return $this->platId;
    }

    public function setPlatId(?int $platId): static
    {
        $this->platId = $platId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }
}
