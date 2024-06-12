<?php
include 'config.php'; 
session_start();

if (isset($_POST['doctor_id'], $_POST['clinic_id'], $_POST['appointment_time'], $_POST['symptoms'])) {
    $doctor_id = $_POST['doctor_id'];
    $clinic_id = $_POST['clinic_id'];
    $appointment_time = $_POST['appointment_time'];
    $symptoms = $_POST['symptoms'];

    try {
        $sql = "INSERT INTO Appointments (appointment_patient_id, appointment_doctor_id, appointment_clinic_id, appointment_time, appointment_symptoms)
                VALUES (:patient_id, :doctor_id, :clinic_id, :appointment_time, :symptoms)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':patient_id', $_SESSION['patient_id'], PDO::PARAM_STR); // 假设用户ID存储在会话中
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
        $stmt->bindParam(':appointment_time', $appointment_time);
        $stmt->bindParam(':symptoms', $symptoms);

        $stmt->execute();
        
        $_SESSION['appointment_message'] = "Appointment successfully booked!";
        header("Location: patient.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "All fields are required.";
}
?>
