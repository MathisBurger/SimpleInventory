<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToMany(targetEntity: PermissionGroups::class, inversedBy: 'tables')]
    private Collection $permissionGroups;

    #[ORM\OneToMany(mappedBy: 'parentTable', targetEntity: TableElement::class)]
    private Collection $elements;

    public function __construct() {
        $this->permissionGroups = new ArrayCollection();
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


}
