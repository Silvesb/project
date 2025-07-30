<?php

use App\Controllers\PatientController;

include('layout.php');

$content = new PatientController('');
$patientData = $content->showAllPatients();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Show all patients</title>
</head>
<body>
    <h1 class="text-center">List of patients</h1><br>
    <div class="container-fluid max-vh-100 d-flex justify-content-center align-items-center">
        <ul class="list-group">
            <?php 
                if ($content) { 
                    if ($patientData) {
                        try {
                            foreach ($patientData as $array) {
                                echo '<li class="list-group-item">';
                                echo "{$array['id']}: Patient: {$array['first_name']} {$array['last_name']}, Gender: {$array['gender']}, Address: {$array['address']}, DOB: {$array['date_of_birth']}\n";
                                echo '</li>';
                            }
                        } catch (Exception $e) {
                            return $e->getMessage();
                        }
                    } else {    
                        echo 'No patients found!';
                    }
                }
            ?> 
        </ul>
    </div>
</body>
</html>
