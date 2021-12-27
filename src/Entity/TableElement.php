<?php

namespace App\Entity;

use App\Repository\TableElementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableElementRepository::class)]
class TableElement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $content = [];

    #[ORM\ManyToOne(targetEntity: Table::class, inversedBy: 'elements')]
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
}
