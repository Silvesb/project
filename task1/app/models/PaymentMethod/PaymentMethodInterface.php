<?php

namespace App\Models\PaymentMethod;

interface PaymentMethodInterface {
    public function getId(): ?int;
    public function getPatientId(): ?int;
    public function getType(): string;
    public function getMaskedNumber(): string;
    public function getStatusText(): string;
    public function getDetails(): string;
    public function isActive(): bool;
    public function setActive(bool $active): void;
}