<?php

namespace App\Models\PaymentMethod;

class CreditCard extends PaymentMethod {
    private string $cardNumber;
    private string $expirationDate;
    private string $cardholderName;

    public function __construct(
        string $cardNumber,
        string $expirationDate,
        string $cardholderName,
        bool $active = true,
        ?int $patientId = null,
        ?int $id = null
    ) {
        $this->cardNumber = $cardNumber;
        $this->expirationDate = $expirationDate;
        $this->cardholderName = $cardholderName;
        $this->status = $active;
        $this->patientId = $patientId;
        $this->id = $id;
    }

    public function getType(): string { return 'CreditCard'; }
    
    public function getMaskedNumber(): string {
        $masked = $this->maskNumber($this->cardNumber, 4);
        return chunk_split($masked, 4, ' ');
    }
    
    public function getStatusText(): string {
        if (!$this->status) return 'Inactive';
        return $this->hasExpired() ? 'Expired' : 'Active';
    }
    
    public function getDetails(): string {
        return $this->getMaskedNumber() . " ({$this->getFormattedExpiration()})";
    }
    
    public function hasExpired(): bool {
        $expiry = \DateTime::createFromFormat('Y-m-d', $this->expirationDate);
        if (!$expiry) return true;
        return $expiry < new \DateTime();
    }
    
    private function getFormattedExpiration(): string {
        return date('m/y', strtotime($this->expirationDate));
    }

    public function isActive(): bool
    {
        return $this->status && !$this->hasExpired();
    }

    // Getters
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function getCardholderName(): string
    {
        return $this->cardholderName;
    }

    // Setters
    public function setCardNumber(string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function setExpirationDate(string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function setCardholderName(string $cardholderName): void
    {
        $this->cardholderName = $cardholderName;
    }
}