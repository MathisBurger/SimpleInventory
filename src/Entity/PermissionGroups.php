<?php

namespace App\Entity;

use App\Repository\PermissionGroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PermissionGroupsRepository::class)]
class PermissionGroups implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $groupColor;

    #[ORM\ManyToMany(targetEntity: Table::class, mappedBy: 'permissionGroups')]
    private Collection $tables;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'permissionGroups')]
    private Collection $users;

    #[Pure] public function __construct()
    {
        $this->tables = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGroupColor(): ?string
    {
        return $this->groupColor;
    }

    public function setGroupColor(?string $groupColor): self
    {
        $this->groupColor = $groupColor;
        return $this;
    }

    /**
     * @return Collection|Table[] All tables of an permission group
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addUser(User $user): self
    {
        $this->users->add($user);
        $user->addPermissionGroup($this);
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        $user->removePermissionGroup($this);
        return $this;
    }

    public function addTable(Table $table): self
    {
        $this->tables->add($table);
        return $this;
    }

    public function removeTable(Table $table): self
    {
        $this->tables->removeElement($table);
        $table->removePermissionGroup($this);
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'groupColor' => $this->groupColor,
            'tables' => array_map(function($table) {
                return [
                    'id' => $table->getId(),
                    'name' => $table->getTableName()
                ];
            }, $this->tables->getValues()),
            'users' => array_map(function($user) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getUserIdentifier()
                ];
            }, $this->users->getValues())
        ];
    }
}
