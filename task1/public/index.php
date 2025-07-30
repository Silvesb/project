<?php 

require_once '../app/config/config.php';
require_once '../vendor/autoload.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
    <div class="list-group">
        <ul class="list-group-action align-middle">
            <a class="list-group-item list-group-item-action" href="app/views/create_patient">Create patient</a>
            <a class="list-group-item list-group-item-action" href="app/views/show_all_patients">Show all patients</a>
            <a class="list-group-item list-group-item-action" href="app/views/show_patient">Search for patient</a>
        </ul>
    </div>
</div>
</body>
</html>