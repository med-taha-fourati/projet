<?php
    $conn = new PDO('mysql:host=localhost;dbname=gestion_reparation', 'root', '');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    function error_403() {
        header('HTTP/1.0 403 Forbidden');
        $contents = file_get_contents('../Vues/assets/403.html');
        exit($contents);
    }
 ?>