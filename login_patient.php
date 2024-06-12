<?php
include 'config.php'; 

session_start();

$error_message = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_username = $_POST['patient_username'];
    $patient_password = $_POST['patient_password'];

    try {
        $sql = "SELECT * FROM Patients WHERE patient_username = :patient_username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':patient_username', $patient_username);
        $stmt->execute();
        
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($patient && password_verify($patient_password, $patient['patient_password'])) {
            $_SESSION['patient_id'] = $patient['patient_id'];
            $_SESSION['patient_username'] = $patient['patient_username'];
            header("Location: patient.php"); 
            exit();
        } else {
            $error_message = "Invalid username or password. Please try again.";
        }
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

include('login_patient.php'); 
?>
