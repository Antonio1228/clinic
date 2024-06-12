<?php
session_start();
include 'config.php';

if (!isset($_SESSION['clinic_id'])) {
    header("Location: login_clinic.php");
    exit();
}

$clinic_id = $_SESSION['clinic_id'];

try {
    $sql = "SELECT a.appointment_patient_id, p.patient_name, d.doctor_name, a.appointment_time, a.appointment_symptoms 
            FROM Appointments a 
            JOIN Patients p ON a.appointment_patient_id = p.patient_id
            JOIN Doctors d ON a.appointment_doctor_id = d.doctor_id
            WHERE a.appointment_clinic_id = :clinic_id
            ORDER BY a.appointment_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "錯誤: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Appointments</title>
    <link rel="stylesheet" href="view_clinic.css">
</head>

<body>
    <header>
        <h1>Your Appointments</h1>
    </header>
    <main>
        <?php if (isset($appointments) && !empty($appointments)): ?>
            <table>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Appointment Time</th>
                    <th>Symptoms</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['appointment_patient_id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_symptoms']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
        <button onclick="location.href='clinic_dashboard.php';" class="action-button">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
