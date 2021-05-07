<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $votes;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $answers;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $views;

    /**
     * @ORM\Column(type="json", nullable=true)
     */

    private $tags;

    /**
     * @ORM\Column(type="integer", nullable=false)
    */

    private $uid;

    /**
     * @ORM\Column(type="integer")
     */

    private $answered;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(?int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getAnswers(): ?int
    {
        return $this->answers;
    }

    public function setAnswers(?int $answers): self
    {
        $this->answers = $answers;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

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

    public function getAnswered(): ?int
    {
        return $this->answered;
    }

    public function setAnswered(int $answered): self
    {
        $this->answered = $answered;

        return $this;
    }

}
