CREATE DATABASE IF NOT EXISTS patient_payment;
USE patient_payment;

CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('M','F') NOT NULL,
    address TEXT NOT NULL
);

CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    type ENUM('CreditCard','ACH') NOT NULL,
    card_number VARCHAR(20),
    account_number VARCHAR(20),
    routing_number VARCHAR(20),
    cardholder_name VARCHAR(100),
    account_holder_name VARCHAR(100),
    expiration_date VARCHAR(10),
    status TINYINT(1) DEFAULT 1,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);