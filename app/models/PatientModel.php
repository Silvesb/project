<?php

namespace App\Models;

use App\Models\PaymentMethod\ACH;
use App\Models\PaymentMethod\CreditCard;
use App\Models\PaymentMethod\PaymentMethodFactory;
use App\Models\PaymentMethod\PaymentMethodInterface;
use Exception;
use PDO;
use PDOException;

class PatientModel {
    protected $id;
    protected $firstName;
    protected $lastName;
    protected $dateOfBirth;
    protected $gender;
    protected $address;
    protected $paymentMethods = [];

    /**
     * @inheritdoc
     */
    public function __construct($id = null) {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @inheritdoc
     */
    public function setDateOfBirth(string $dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @inheritdoc
     */
    public function setGender(string $gender) {
        $this->gender = $gender;
    }

    /**
     * @inheritdoc
     */
    public function setAddress(string $address) {
        $this->address = $address;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function getDateOfBirth(): string {
        return $this->dateOfBirth;
    }

    /**
     * @inheritdoc
     */
    public function getGender(): string {
        return $this->gender;
    }

    /**
     * @inheritdoc
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentMethods(): array {
        return $this->paymentMethods;
    }

    /**
     * @inheritdoc
     */
    public function addPaymentMethod(PaymentMethodInterface $paymentMethod) {
        $this->paymentMethods[] = $paymentMethod;
    }

    /**
     * @return array|null
     */
    public function getPatient(PDO $pdo, $data = []) {
        if (empty($data)) {
            return null;  // No search criteria provided
        }

        // Initialize variables for dynamic query building
        $conditions = [];
        $params = [];
        $queryTypes = [];

        // Process ID if provided
        if (!empty($data['id'])) {
            $conditions[] = "patients.id = :id";
            $params[':id'] = $data['id'];
            $queryTypes[] = 'id';
        }

        // Process name if provided
        if (!empty($data['name'])) {
            $conditions[] = "LOWER(CONCAT(patients.first_name, ' ', patients.last_name)) LIKE LOWER(:name)";
            $params[':name'] = '%' . $data['name'] . '%';
            $queryTypes[] = 'name';
        }

        // Handle case where both are provided
        if (count($queryTypes) === 2) {
            $whereClause = implode(' AND ', $conditions);
        }
        // Handle single search criteria
        elseif (count($queryTypes) === 1) {
            $whereClause = $conditions[0];
        }
        // Invalid criteria
        else {
            return null;
        }

        try {
            // ... existing query building code ...

            // Build SQL query with JOIN to payment_methods
            $sql = "SELECT 
                        patients.*,
                        payment_methods.id AS payment_method_id,
                        payment_methods.type AS payment_method_type,
                        payment_methods.card_number AS payment_method_card_number,
                        payment_methods.account_number AS payment_method_account_number,
                        payment_methods.routing_number AS payment_method_routing_number,
                        payment_methods.cardholder_name AS payment_method_cardholder_name,
                        payment_methods.account_holder_name AS payment_method_account_holder_name,
                        payment_methods.expiration_date AS payment_method_expiration_date,
                        payment_methods.status AS payment_method_status
                    FROM patients
                    LEFT JOIN payment_methods ON patients.id = payment_methods.patient_id
                    WHERE $whereClause
                    ORDER BY patients.id, payment_methods.id";

            $patientQuery = $pdo->prepare($sql);
            $patientQuery->execute($params);

            $rows = $patientQuery->fetchAll(PDO::FETCH_ASSOC);
            $patients = [];
            
            foreach ($rows as $row) {
                $patientId = $row['id'];
                
                if (!isset($patients[$patientId])) {
                    $patients[$patientId] = new Patient(
                        $row['id'],
                        $row['first_name'],
                        $row['last_name'],
                        $row['date_of_birth'],
                        $row['gender'],
                        $row['address']
                    );
                }
                
                if ($row['payment_method_id'] !== null) {
                    $paymentMethod = PaymentMethodFactory::create(
                        $row['payment_method_type'],
                        [
                            'id' => $row['payment_method_id'],
                            'card_number' => $row['payment_method_card_number'],
                            'account_number' => $row['payment_method_account_number'],
                            'routing_number' => $row['payment_method_routing_number'],
                            'cardholder_name' => $row['payment_method_cardholder_name'],
                            'account_holder_name' => $row['payment_method_account_holder_name'],
                            'expiration_date' => $row['payment_method_expiration_date'],
                            'status' => (bool)$row['payment_method_status'],
                            'patient_id' => $patientId
                        ]
                    );
                    
                    $patients[$patientId]->addPaymentMethod($paymentMethod);
                }
            }

            return array_values($patients);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Shows all patients
     * @return array|null
     */
    public function getAllPatients(PDO $pdo) {
        try {
            $patientData = null;
            $patientsData = [];
            $patientsQuery = $pdo->query('SELECT * FROM patients ORDER BY id ASC');
            
            foreach ($patientsQuery->fetchAll(PDO::FETCH_ASSOC) as $patientData[]) {
                array_merge($patientsData, $patientData);
            }

            if (!$patientData) {
                return null;
            }

            return $patientData;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Creates patient
     * @param object PDO
     * @return bool
     */
    public function createPatient(PDO $pdo) {
        try {
            $query = $pdo->prepare("INSERT INTO patients 
                (first_name, last_name, date_of_birth, gender, address) 
                VALUES (?, ?, ?, ?, ?)
            ");

            $query->execute([
                $this->getFirstName(), 
                $this->getLastName(), 
                $this->getDateOfBirth(), 
                $this->getGender(), 
                $this->getAddress()
            ]);

            $insertedPatientId = $pdo->lastInsertId();

            // Clear existing payment methods
            $stmt = $pdo->prepare("DELETE FROM payment_methods WHERE patient_id = ?");
            $stmt->execute([$insertedPatientId]);

            // Save payment methods
            foreach ($this->paymentMethods as $method) {
                $method->setPatientId($insertedPatientId);
                
                $type = ($method instanceof CreditCard) ? 'CreditCard' : 'ACH';
                $stmt = $pdo->prepare("INSERT INTO payment_methods (patient_id, type, card_number, account_number, routing_number, cardholder_name, account_holder_name, expiration_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $cardNumber = ($method instanceof CreditCard) ? $method->getCardNumber() : null;
                $accountNumber = ($method instanceof ACH) ? $method->getAccountNumber() : null;
                $routingNumber = ($method instanceof ACH) ? $method->getRoutingNumber() : null;
                $cardholderName = ($method instanceof CreditCard) ? $method->getCardholderName() : null;
                $accountHolderName = ($method instanceof ACH) ? $method->getAccountHolderName() : null;
                $expirationDate = ($method instanceof CreditCard) ? $method->getExpirationDate() : null;
                $status = $method->isActive() ? 1 : 0;
                
                $stmt->execute([
                    $insertedPatientId,
                    $type,
                    $cardNumber,
                    $accountNumber,
                    $routingNumber,
                    $cardholderName,
                    $accountHolderName,
                    $expirationDate,
                    $status
                ]);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw $e;
        }
    }
}