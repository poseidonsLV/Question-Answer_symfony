<?php

namespace App\Entity;

use App\Repository\QuestionVotesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionVotesRepository::class)
 */
class QuestionVotes
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
    private $qid;

    /**
     * @ORM\Column(type="integer")
     */
    private $uid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQid(): ?int
    {
        return $this->qid;
    }

    public function setQid(int $qid): self
    {
        $this->qid = $qid;

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
