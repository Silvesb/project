<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Controllers\Controller;
use App\Models\PaymentMethod\ACH;
use App\Models\PaymentMethod\CreditCard;

class PatientController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Shows single patient.
     * @param array $data
     * @return array $data
     */
    public function showPatient($data) {
        $patient = new PatientModel();

        $filteredData = $this->filterOutput($data);
        
        return $patient->getPatient($this->pdo, $filteredData);
    }

    /**
     * @return array|null
     */
    public function showAllPatients() {
        $patient = new PatientModel();

        return $patient->getAllPatients($this->pdo);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function createPatient($data) {
        if ($data) {
            $transformData = $this->transformInput($data);

            $patient = new PatientModel();
            $patient->setFirstName($transformData['first_name']);
            $patient->setLastName($transformData['last_name']);
            $patient->setDateOfBirth($transformData['date_of_birth']);
            $patient->setGender($transformData['gender']);
            $patient->setAddress($transformData['address']);

                        
            // Process payment methods
            if (!empty($transformData['payment_methods'])) {
                foreach ($transformData['payment_methods'] as $method) {

                    if ($method['type'] === 'CreditCard') {
                        $paymentMethod = new CreditCard(
                            $method['card_number'],
                            $method['expiration_date'],
                            $method['cardholder_name']
                        );
                    } else { 
                        $paymentMethod = new ACH(
                            $method['account_number'], 
                            $method['routing_number'],
                            $method['account_holder_name']
                        );
                    }
                    $patient->addPaymentMethod($paymentMethod);
                }
            }

            return $patient->createPatient($this->pdo);
        } else {
            return false;
        }
    }

    /**
     * Used while creating patient, removes empty/unused fields.
     * Makes array readable
     * @param array $data
     * @return array
     */
    function transformInput(array $input): array {
        $personalKeys = ['first_name', 'last_name', 'date_of_birth', 'gender', 'address'];
        $result = [];

        $filtered = $this->filterOutput($input);
        
        foreach ($personalKeys as $key) {
            if (isset($filtered[$key])) {
                $result[$key] = $filtered[$key];
            }
        }
        
        $paymentMethods = [];
        foreach ($filtered as $key => $value) {
            if (in_array($key, $personalKeys)) {
                continue;
            }
            
            // Match keys with pattern: {name}_{index}
            if (preg_match('/^(.+?)_(\d+)$/', $key, $matches)) {
                $baseKey = $matches[1];
                $index = (int)$matches[2];
                
                if (!isset($paymentMethods[$index])) {
                    $paymentMethods[$index] = [];
                }
                
                if ($baseKey === 'status') {
                    $paymentMethods[$index][$baseKey] = ($value === 'on');
                } else {
                    $paymentMethods[$index][$baseKey] = $value;
                }
            }
        }
        
        foreach ($paymentMethods as &$method) {
            if (!array_key_exists('status', $method)) {
                $method['status'] = false;
            }
        }
        unset($method);
        
        ksort($paymentMethods);
        $result['payment_methods'] = $paymentMethods;
        
        return $result;
    }

    /**
     * Removes blank fields.
     * @param array $data
     * @return array $data
     */
    function filterOutput($data) {
        $filtered = array_filter($data, function ($element) {
            return is_string($element) && '' !== trim($element);
        });

        return $filtered;
    }
}
