<?php
session_start();
include 'config.php';

if (!isset($_SESSION['clinic_id'])) {
    header("Location: login_clinic.php");
    exit();
}

$clinic_id = $_SESSION['clinic_id'];

try {
    $sql = "SELECT clinic_name, clinic_open_time, clinic_phone, clinic_address FROM Clinics WHERE clinic_id = :clinic_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
    $stmt->execute();
    $clinic = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "錯誤: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clinic Dashboard</title>
    <link rel="stylesheet" href="clinic.css">
</head>

<body>
    <header>
        <h1>Welcome to Clinic Dashboard</h1>
    </header>
    <main>
        <section class="info-section">
            <h2>Clinic Information</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($clinic['clinic_name']); ?></p>
            <p><strong>Open Time:</strong> <?php echo htmlspecialchars($clinic['clinic_open_time']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($clinic['clinic_phone']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($clinic['clinic_address']); ?></p>
        </section>
        <section class="action-section">
            <button onclick="location.href='view_appointments_clinic.php';" class="action-button">View Appointments</button>
            <button onclick="location.href='update_clinic_info.php';" class="action-button">Update Information</button>
            <button onclick="location.href='view_reviews_clinic.php';" class="action-button">View Reviews</button>
            <button onclick="location.href='logout.php';" class="action-button">Log Out</button>
        </section>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
