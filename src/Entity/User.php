<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
    */
    private $level;

    /**
     * @ORM\Column(type="string")
    */
    private $avatar;

    /**
     * @ORM\Column(type="integer")
    */
    private $admin;

    /**
     * @ORM\Column(type="integer")
    */
    private $moderator;

    /**
     * @ORM\Column(type="integer")
    */
    private $questionsCount;

    /**
     * @ORM\Column(type="integer")
    */
    private $answeredCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $followerCount;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAdmin(): ?int
    {
        return $this->admin;
    }

    public function setAdmin(int $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getModerator(): ?int
    {
        return $this->moderator;
    }

    public function setModerator(int $moderator): self
    {
        $this->moderator = $moderator;

        return $this;
    }

    public function getQuestionsCount(): ?int
    {
        return $this->questionsCount;
    }

    public function setQuestionsCount(int $questionsCount): self
    {
        $this->questionsCount = $questionsCount;

        return $this;
    }

    public function getAnsweredCount(): ?int
    {
        return $this->answeredCount;
    }

    public function setAnsweredCount(int $answeredCount): self
    {
        $this->answeredCount = $answeredCount;

        return $this;
    }

    public function getFollowerCount(): ?int
    {
        return $this->followerCount;
    }

    public function setFollowerCount(int $followerCount): self
    {
        $this->followerCount = $followerCount;

        return $this;
    }
}
