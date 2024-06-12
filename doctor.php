<?php
session_start();

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login_doctor.php");
    exit();
}

include('doctor_dashboard.html');
?>
