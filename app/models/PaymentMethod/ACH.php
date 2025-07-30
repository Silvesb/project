<?php

namespace App\Models\PaymentMethod;

class ACH extends PaymentMethod {
    private string $accountNumber;
    private string $routingNumber;
    private string $accountHolderName;

    public function __construct(
        string $accountNumber,
        string $routingNumber,
        string $accountHolderName,
        bool $active = true,
        ?int $patientId = null,
        ?int $id = null
    ) {
        $this->accountNumber = $accountNumber;
        $this->routingNumber = $routingNumber;
        $this->accountHolderName = $accountHolderName;
        $this->status = $active;
        $this->patientId = $patientId;
        $this->id = $id;
    }

    public function getType(): string { return 'ACH'; }
    
    public function getMaskedNumber(): string {
        return $this->maskNumber($this->accountNumber, 4);
    }
    
    public function getStatusText(): string {
        return $this->status ? 'Active' : 'Inactive';
    }
    
    public function getDetails(): string {
        return $this->getMaskedNumber();
    }

    /**
     * Get masked account number
     */
    public function getMaskedCardNumber(): string
    {
        return $this->maskNumber($this->accountNumber);
    }

    // Getters
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getRoutingNumber(): string
    {
        return $this->routingNumber;
    }

    public function getAccountHolderName(): string
    {
        return $this->accountHolderName;
    }

    // Setters
    public function setAccountNumber(string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    public function setRoutingNumber(string $routingNumber): void
    {
        $this->routingNumber = $routingNumber;
    }

    public function setAccountHolderName(string $accountHolderName): void
    {
        $this->accountHolderName = $accountHolderName;
    }
}