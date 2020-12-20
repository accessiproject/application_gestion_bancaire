<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
	 * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="transactions")
	 * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity=Beneficiary::class, inversedBy="transactions")
     */
    private $beneficiary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $debit;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $credit;

    /**
     * @ORM\Column(type="datetime")
     */
    private $achievedat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $choice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getBeneficiary(): ?Beneficiary
    {
        return $this->beneficiary;
    }

    public function setBeneficiary(?Beneficiary $beneficiary): self
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDebit(): ?float
    {
        return $this->debit;
    }

    public function setDebit(?float $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getCredit(): ?float
    {
        return $this->credit;
    }

    public function setCredit(?float $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getAchievedat(): ?\DateTimeInterface
    {
        return $this->achievedat;
    }

    public function setAchievedat(\DateTimeInterface $achievedat): self
    {
        $this->achievedat = $achievedat;

        return $this;
    }

    public function getChoice(): ?bool
    {
        return $this->choice;
    }

    public function setChoice(bool $choice): self
    {
        $this->choice = $choice;

        return $this;
    }
}
