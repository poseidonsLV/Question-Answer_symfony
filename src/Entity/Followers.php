<?php

namespace App\Entity;

use App\Repository\FollowersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FollowersRepository::class)
 */
class Followers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
    */
    private $fid;

    /**
     * @ORM\Column(type="integer")
    */
    private $uid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFid(): ?int
    {
        return $this->fid;
    }

    public function setFid(int $fid): self
    {
        $this->fid = $fid;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}
