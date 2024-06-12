<?php
include 'config.php'; 

session_start();

$patient_id = $_POST['patient_id'];
$patient_name = $_POST['patient_name'];
$patient_username = $_POST['patient_username'];
$patient_email = $_POST['patient_email'];
$patient_password = password_hash($_POST['patient_password'], PASSWORD_DEFAULT); // 使用password_hash進行密碼加密
$patient_gender = $_POST['patient_gender'];
$patient_birth_date = $_POST['patient_birth_date'];
$patient_age = $_POST['patient_age'];
$patient_phone = $_POST['patient_phone'];
$patient_address = $_POST['patient_address'];

if (!preg_match('/^[A-Z]\d{9}$/', $patient_id)) {
    die("Invalid patient ID format. It should be one uppercase letter followed by 9 digits.");
}

try {
    $sql = "SELECT COUNT(*) FROM Patients WHERE patient_id = :patient_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        die("Patient ID already exists.");
    }

    $sql = "SELECT COUNT(*) FROM Patients WHERE patient_username = :patient_username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_username', $patient_username);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        die("Username already exists.");
    }

    $sql = "INSERT INTO Patients (patient_id, patient_name, patient_username, patient_email, patient_password, patient_gender, patient_birth_date, patient_age, patient_phone, patient_address) VALUES (:patient_id, :patient_name, :patient_username, :patient_email, :patient_password, :patient_gender, :patient_birth_date, :patient_age, :patient_phone, :patient_address)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':patient_name', $patient_name);
    $stmt->bindParam(':patient_username', $patient_username);
    $stmt->bindParam(':patient_email', $patient_email);
    $stmt->bindParam(':patient_password', $patient_password);
    $stmt->bindParam(':patient_gender', $patient_gender);
    $stmt->bindParam(':patient_birth_date', $patient_birth_date);
    $stmt->bindParam(':patient_age', $patient_age);
    $stmt->bindParam(':patient_phone', $patient_phone);
    $stmt->bindParam(':patient_address', $patient_address);
    
    $stmt->execute();
    echo "New patient registered successfully!";
    header("Location: index.html");
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
