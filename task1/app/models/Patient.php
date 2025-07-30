<?php

namespace App\Models;

use App\Models\PaymentMethod\PaymentMethodInterface;

class Patient {
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $dateOfBirth;
    private string $gender;
    private string $address;
    private array $paymentMethods = [];

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $dateOfBirth,
        string $gender,
        string $address
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->address = $address;
    }

    public function getId(): int { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getDateOfBirth(): string { return $this->dateOfBirth; }
    public function getGender(): string { return $this->gender; }
    public function getAddress(): string { return $this->address; }
    
    public function addPaymentMethod(PaymentMethodInterface $paymentMethod): void {
        $this->paymentMethods[] = $paymentMethod;
    }
    
    public function getPaymentMethods(): array {
        return $this->paymentMethods;
    }
    
    public function getFormattedInfo(): string {
        return sprintf(
            "Patient: %s %s, Gender: %s, Address: %s, DOB: %s",
            $this->firstName,
            $this->lastName,
            $this->gender,
            $this->address,
            $this->dateOfBirth
        );
    }
}