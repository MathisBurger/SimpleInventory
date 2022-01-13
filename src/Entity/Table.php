<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToMany(targetEntity: PermissionGroups::class, inversedBy: 'tables')]
    private Collection $permissionGroups;

    #[ORM\OneToMany(mappedBy: 'parentTable', targetEntity: TableElement::class, cascade: ['remove'])]
    private Collection $elements;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $tableName;

    public function __construct() {
        $this->permissionGroups = new ArrayCollection();
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addPermissionGroup(PermissionGroups $group): self
    {
        $this->permissionGroups->add($group);
        $group->addTable($this);
        return $this;
    }

    public function removePermissionGroup(PermissionGroups $group): self
    {
        $this->permissionGroups->removeElement($group);
        return $this;
    }

    public function addTableElement(TableElement $tableElement): self
    {
        $this->elements->add($tableElement);
        $tableElement->setParentTable($this);
        return $this;
    }

    public function removeTableElement(TableElement $tableElement): self
    {
        $this->elements->removeElement($tableElement);
        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(?string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'tableName' => $this->tableName,
            'elements' => array_map(function($element) {
                return ['id' => $element->getId(), 'content' => $element->getContent()];
            }, $this->elements->getValues())
        ];
    }
}
