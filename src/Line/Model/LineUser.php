<?php

declare(strict_types=1);

namespace Bonn\Line\Model;

class LineUser implements LineUserInterface
{
    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $lineIdentifier;

    /** @var bool */
    protected $enabled = false;

    /** @var string|null */
    protected $scope;

    /** @var array */
    protected $info = [];

    /** @var string|null */
    protected $note;

    /** @var string|null */
    protected $name;

    /** @var array */
    protected $lineState = [];

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineIdentifier(): ?string
    {
        return $this->lineIdentifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setLineIdentifier(?string $lineIdentifier): void
    {
        $this->lineIdentifier = $lineIdentifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * {@inheritdoc}
     */
    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function getInfo(): array
    {
        return (array) $this->info;
    }

    /**
     * {@inheritdoc}
     */
    public function setInfo(array $info): void
    {
        $this->info = $info;
    }

    /**
     * {@inheritdoc}
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * {@inheritdoc}
     */
    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineState(): array
    {
        return (array) $this->lineState;
    }

    /**
     * {@inheritdoc}
     */
    public function setLineState(array $lineState): void
    {
        $this->lineState = $lineState;
    }
}
