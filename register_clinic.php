<?php
include 'config.php'; 

session_start();

$clinic_name = $_POST['clinic_name'];
$clinic_password = password_hash($_POST['clinic_password'], PASSWORD_DEFAULT); // 使用password_hash進行密碼加密
$clinic_phone = $_POST['clinic_phone'];
$clinic_address = $_POST['clinic_address'];
$clinic_department = $_POST['clinic_department'];

try {
    $sql = "SELECT COUNT(*) FROM Clinics WHERE clinic_name = :clinic_name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':clinic_name', $clinic_name);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        die("Clinic name already exists.");
    }

    $sql = "INSERT INTO Clinics (clinic_name, clinic_password, clinic_phone, clinic_address, clinic_department) VALUES (:clinic_name, :clinic_password, :clinic_phone, :clinic_address, :clinic_department)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':clinic_name', $clinic_name);
    $stmt->bindParam(':clinic_password', $clinic_password);
    $stmt->bindParam(':clinic_phone', $clinic_phone);
    $stmt->bindParam(':clinic_address', $clinic_address);
    $stmt->bindParam(':clinic_department', $clinic_department);
    
    $stmt->execute();
    echo "New clinic registered successfully!";
    header("Location: index.html"); 
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
