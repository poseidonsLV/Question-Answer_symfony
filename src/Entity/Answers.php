<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answers
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

    private $qid; // Quesstion id

    /**
     * @ORM\Column(type="integer")
     */

    private $uid; // User id ( who submited this answer )

    /**
     * @ORM\Column(type="string", nullable=false)
     */

    private $description;

    /**
     * @ORM\Column(type="string", nullable=false)
     */

    private $date;

    public function getId(): ?int {
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
