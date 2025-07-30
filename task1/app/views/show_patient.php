<?php

use App\Controllers\PatientController;

include('layout.php');

$patientController = new PatientController();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a patient</title>
</head>
<body>
    <h1 class="text-center">Search for a patient</h1><br>
    <div class="container max-vh-100 d-flex justify-content-center">
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="name">Search by name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe">
            </div>
            <div class="form-group mb-3">
                <label for="id">Search by ID</label>
                <input type="text" class="form-control" id="id" name="id">
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
    <br>
<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
    <div class="w-100" style="max-width: 800px;">
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <?php 
            $patientData = $patientController->showPatient($_POST);
            if ($patientData) : ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5 mb-0">Patient Search Results</h2>
                    </div>
                    
                    <div class="card-body">
                        <?php foreach ($patientData as $patient) : ?>
                            <div class="card mb-4 border-primary">
                                <div class="card-header bg-light">
                                    <h3 class="h6 mb-0">
                                        <?= htmlspecialchars($patient->getFormattedInfo()) ?>
                                    </h3>
                                </div>
                                
                                <div class="card-body">
                                    <h4 class="h6 mt-4 mb-3 border-bottom pb-2">Payment Methods</h4>
                                    
                                    <?php if (!empty($patient->getPaymentMethods())) : ?>
                                        <ul class="list-group">
                                            <?php foreach ($patient->getPaymentMethods() as $index => $payment) : ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Payment Method #<?= $index + 1 ?>:</strong>
                                                        <?= htmlspecialchars($payment->getType()) ?> | 
                                                        Masked Number: <?= htmlspecialchars($payment->getDetails()) ?> | 
                                                        Status: <?= htmlspecialchars($payment->getStatusText()) ?>
                                                    </div>
                                                    <span class="badge bg-<?= 
                                                        $payment->getStatusText() === 'Active' ? 'success' : 
                                                        ($payment->getStatusText() === 'Expired' ? 'danger' : 'secondary')
                                                    ?>">
                                                        <?= htmlspecialchars($payment->getStatusText()) ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else : ?>
                                        <div class="alert alert-info mb-0">No payment methods found</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-warning text-center">
                    <h2 class="h4">No patients found!</h2>
                    <p class="mb-0">Please try different search criteria</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>