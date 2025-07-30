<?php

namespace App\Models\PaymentMethod;

abstract class PaymentMethod implements PaymentMethodInterface {
    protected ?int $id = null;
    protected ?int $patientId = null;
    protected bool $status = true;

    public function getId(): ?int { 
        return $this->id; 
    }

    public function setId(int $id): void { 
        $this->id = $id; 
    }

    public function getPatientId(): ?int { 
        return $this->patientId; 
    }

    public function setPatientId(int $patientId): void { 
        $this->patientId = $patientId; 
    }

    public function isActive(): bool { 
        return $this->status; 
    }

    public function setActive(bool $active): void {
        $this->status = $active; 
    }
    
    protected function maskNumber(string $number, int $visible = 4): string {
        $cleanNumber = preg_replace('/\D/', '', $number);
        $length = strlen($cleanNumber);
        $masked = str_repeat('*', max(0, $length - $visible));
        return $masked . substr($cleanNumber, -$visible);
    }
    
    abstract public function getStatusText(): string;
    abstract public function getDetails(): string;
}