<?php

declare(strict_types=1);

namespace Bonn\Line\Model;

interface LineUserInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void;

    /**
     * @return string|null
     */
    public function getLineIdentifier(): ?string;

    /**
     * @param string|null $lineIdentifier
     *
     * @return void
     */
    public function setLineIdentifier(?string $lineIdentifier): void;

    /**
     * @return string|null
     */
    public function getScope(): ?string;

    /**
     * @param string|null $scope
     *
     * @return void
     */
    public function setScope(?string $scope): void;

    /**
     * @return array
     */
    public function getInfo(): array;

    /**
     * @param array $info
     *
     * @return void
     */
    public function setInfo(array $info): void;

    /**
     * @return string|null
     */
    public function getNote(): ?string;

    /**
     * @param string|null $note
     *
     * @return void
     */
    public function setNote(?string $note): void;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * @return array
     */
    public function getLineState(): array;

    /**
     * @param array $lineState
     *
     * @return void
     */
    public function setLineState(array $lineState): void;
}
