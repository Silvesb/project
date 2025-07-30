<?php

use App\Controllers\PatientController;

include('layout.php');

$patientController = new PatientController();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <title>Create a patient</title>
</head>
<body>
    <script src="js/script.js"></script>
    <h1 class="text-center">Create a new patient</h1><br>
    <div class="container max-vh-100 d-flex justify-content-center">
        <form id="patientForm" action="" method="POST">
            <div class="form-group mb-3">
                <label for="first_name">First name</label>
                <input required type="text" class="form-control" id="firstName" name="first_name" placeholder="John">
            </div>
            <div class="form-group mb-3">
                <label for="last_name">Last name</label>
                <input required type="text" class="form-control" id="lastName" name="last_name" placeholder="Doe">
            </div>
            <div class="form-group mb-3">
                <label for="date_of_birth">Date of birth</label>
                <br>
                <input required type="date" name="date_of_birth" id="dateOfBirth"> 
            </div>
            <div class="form-group mb-3">
                <label for="gender">Gender:</label>
                <input type="radio" id="male" name="gender" value="m" checked required>
                <label for="gender_male">Male</label>
                <input type="radio" id="female" name="gender" value="f">
                <label for="gender_female">Female</label>
            </div>
            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea required class="form-control" name="address" id="address" rows="3"></textarea>
            </div>
            <div class="text-center">
                <input type="button" class="btn btn-secondary" id="paymentMethodButton" name="paymentMethodButton" value="Add a payment method" onclick="addPaymentMethodFields('patientForm')" />
            </div>
            <br><br>
            <div class="form-group mb-3" id="separateForms">
                
            </div>

            <div class="form-group mb-3 text-center">
                <input class="btn btn-primary" type="submit" value="Submit" id="submit" onclick="getAllPaymentData()">
            </div>
        </form>
    </div>
    <?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($patientController->createPatient($_POST)) {
            echo '<p class="text-center">Patient created successfully!</p>';
        } else {
            echo '<p class="text-center">Something went wrong..</p>';
        }
    }
    ?>
</body>
</html>