<?php

namespace App\Entity;

use App\Repository\TableElementRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: TableElementRepository::class)]
class TableElement implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $content = [];

    #[ORM\ManyToOne(targetEntity: Table::class, inversedBy: 'elements')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Table $parentTable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setParentTable(?Table $table): self
    {
        $this->parentTable = $table;
        return $this;
    }

    public function getParentTable(): ?Table
    {
        return $this->parentTable;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'content' => $this->content
        ];
    }
}
