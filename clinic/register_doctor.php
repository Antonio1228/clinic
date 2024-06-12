<?php
include 'config.php';

session_start();

$doctor_name = $_POST['doctor_name'];
$doctor_gender = $_POST['doctor_gender'];
$doctor_profession = $_POST['doctor_profession'];
$clinic_id = $_POST['clinic_id'];
$doctor_password = password_hash($_POST['doctor_password'], PASSWORD_DEFAULT); 

try {
    $sql = "SELECT COUNT(*) FROM Doctors WHERE doctor_name = :doctor_name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':doctor_name', $doctor_name);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        die("Doctor name already exists.");
    }

    $sql = "INSERT INTO Doctors (doctor_name, doctor_gender, doctor_profession, doctor_password, clinic_id) VALUES (:doctor_name, :doctor_gender, :doctor_profession, :doctor_password, :clinic_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':doctor_name', $doctor_name);
    $stmt->bindParam(':doctor_gender', $doctor_gender);
    $stmt->bindParam(':doctor_profession', $doctor_profession);
    $stmt->bindParam(':doctor_password', $doctor_password);
    $stmt->bindParam(':clinic_id', $clinic_id);
    
    $stmt->execute();
    echo "New doctor registered successfully!";
    header("Location: index.html");
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
