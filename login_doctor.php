<?php
include 'config.php'; 
session_start();

$error_message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_name = $_POST['doctor_name'];
    $doctor_password = $_POST['doctor_password'];

    try {
        $sql = "SELECT * FROM Doctors WHERE doctor_name = :doctor_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':doctor_name', $doctor_name);
        $stmt->execute();
        
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor && password_verify($doctor_password, $doctor['doctor_password'])) {
            $_SESSION['doctor_id'] = $doctor['doctor_id'];
            $_SESSION['doctor_name'] = $doctor['doctor_name'];
            header("Location: doctor.html"); 
            exit();
        } else {
            $error_message = "Invalid name or password. Please try again.";
        }
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

include('login_doctor.html'); 
?>
