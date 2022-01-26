<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    public const STATUS_NOT_A_COMMAND = 0;
    public const STATUS_WAITING = 1;
    public const STATUS_ACCEPTED = 2;
    public const STATUS_REFUSED = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un nom.")
     */
    private string $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $savedAt;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="command", orphanRemoval=true, cascade={"persist"})
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Vous devez entrer au minimum un article.",
     * )
     * @var Collection<Article>
     */
    private Collection $articles;

    /**
     * @ORM\Column(type="integer")
     */
    private int $length;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir une référence.")
     */
    private string $reference;

    /**
     * @ORM\Column(type="integer")
     */
    private int $width;

    /**
     * @ORM\Column(type="integer")
     */
    private int $withdrawLength;

    /**
     * @ORM\Column(type="integer")
     */
    private int $withdrawWidth;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSavedAt(): ?\DateTimeInterface
    {
        return $this->savedAt;
    }

    public function setSavedAt(?\DateTimeInterface $savedAt): self
    {
        $this->savedAt = $savedAt;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCommand($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->articles->removeElement($article);

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getWithdrawLength(): ?int
    {
        return $this->withdrawLength;
    }

    public function setWithdrawLength(int $withdrawLength): self
    {
        $this->withdrawLength = $withdrawLength;

        return $this;
    }

    public function getWithdrawWidth(): ?int
    {
        return $this->withdrawWidth;
    }

    public function setWithdrawWidth(int $withdrawWidth): self
    {
        $this->withdrawWidth = $withdrawWidth;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getStatusString(): string
    {
        if ($this->status === self::STATUS_ACCEPTED) {
            return "Accepté";
        }
        if ($this->status === self::STATUS_REFUSED) {
            return "Refusé";
        }
        if ($this->status === self::STATUS_WAITING) {
            return "En attente";
        }
        if ($this->status === self::STATUS_NOT_A_COMMAND) {
            return "En calcul";
        }
        return "Erreur";
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
