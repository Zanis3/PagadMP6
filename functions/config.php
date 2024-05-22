<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$serverName = 'localhost';
$name = 'root';
$pass = '';
$dbName = 'pagadmp6_guestreservation';

$connection = new mysqli($serverName, $name, $pass, $dbName);