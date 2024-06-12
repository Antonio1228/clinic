<?php
session_start();
include 'config.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login_doctor.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

try {
    $sql = "SELECT a.appointment_patient_id, p.patient_name, c.clinic_name, a.appointment_time, a.appointment_symptoms 
            FROM Appointments a 
            JOIN Patients p ON a.appointment_patient_id = p.patient_id
            JOIN Clinics c ON a.appointment_clinic_id = c.clinic_id
            WHERE a.appointment_doctor_id = :doctor_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "錯誤: " . $e->getMessage();
}

include('view_appointments.html');
?>
