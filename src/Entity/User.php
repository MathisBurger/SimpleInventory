<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_MANAGER = 'ROLE_MANAGER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $token;

    /**
     * @return int|null The id of the user
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array All roles of the user
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        // nothing implemented
    }

    /**
     * @return string The field the user uses to log in
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return string|null The hashed password of the user
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the new user password
     *
     * @param string $password The new hashed password for the user
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string The token used for auth
     */
    public function getToken(): string {
        return $this->token;
    }

    /**
     * Sets a new user token for auth
     *
     * @param string $token The new user token
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Sets the username of the user
     *
     * @param string $username The username of the user
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }
}
