<?php
include 'config.php'; 
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.html"); 
    exit();
}

$appointment_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($appointment_id) {
    try {
        $sql = "DELETE FROM Appointments WHERE appointment_id = :appointment_id AND appointment_patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $stmt->bindParam(':patient_id', $_SESSION['patient_id'], PDO::PARAM_STR);
        $stmt->execute();
        header("Location: patient.php"); 
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid appointment ID.";
}
?>
