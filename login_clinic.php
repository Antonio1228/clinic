<?php
include 'config.php';

session_start();

$error_message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clinic_name = $_POST['clinic_name'];
    $clinic_password = $_POST['clinic_password'];

    try {
        $sql = "SELECT * FROM Clinics WHERE clinic_name = :clinic_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':clinic_name', $clinic_name);
        $stmt->execute();
        
        $clinic = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clinic && password_verify($clinic_password, $clinic['clinic_password'])) {
            $_SESSION['clinic_id'] = $clinic['clinic_id'];
            $_SESSION['clinic_name'] = $clinic['clinic_name'];
            header("Location: clinic_dashboard.php"); 
            exit();
        } else {
            $error_message = "Invalid clinic name or password. Please try again.";
        }
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

include('login_clinic.html'); 
?>
