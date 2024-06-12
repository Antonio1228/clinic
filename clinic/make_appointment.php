<?php
include 'config.php';
session_start();

$doctor_id = $_POST['doctor_id'] ?? '';
$clinic_id = $_POST['clinic_id'] ?? '';
$appointment_time = $_POST['appointment_time'] ?? '';
$symptoms = $_POST['symptoms'] ?? '';

try {
    $sql = "INSERT INTO Appointments (appointment_patient_id, appointment_doctor_id, appointment_clinic_id, appointment_time, appointment_symptoms) VALUES (:patient_id, :doctor_id, :clinic_id, :appointment_time, :symptoms)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_id', $_SESSION['patient_id'], PDO::PARAM_STR);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
    $stmt->bindParam(':appointment_time', $appointment_time, PDO::PARAM_STR);
    $stmt->bindParam(':symptoms', $symptoms, PDO::PARAM_STR);
    $stmt->execute();

    echo "<p>Appointment successfully booked!</p>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
