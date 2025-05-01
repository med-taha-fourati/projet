<?php
    $conn = new PDO('mysql:host=localhost;dbname=gestion_reparation', 'root', '');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
 ?>