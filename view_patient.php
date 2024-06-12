<?php
include 'config.php'; 
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.html"); 
    exit();
}

$patient_id = $_SESSION['patient_id'];

try {
    $sql = "SELECT Appointments.appointment_id, Doctors.doctor_name, Clinics.clinic_name, Appointments.appointment_time, Appointments.appointment_symptoms 
            FROM Appointments 
            JOIN Doctors ON Appointments.appointment_doctor_id = Doctors.doctor_id
            JOIN Clinics ON Appointments.appointment_clinic_id = Clinics.clinic_id
            WHERE Appointments.appointment_patient_id = :patient_id
            ORDER BY Appointments.appointment_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_STR);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Appointments</title>
    <link rel="stylesheet" href="view_patient.css">
</head>

<body>
    <header>
        <h1>Your Appointments</h1>
    </header>
    <main>
        <?php
        if ($appointments) {
            echo "<table>";
            echo "<tr><th>Doctor Name</th><th>Clinic Name</th><th>Appointment Time</th><th>Symptoms</th><th>Action</th></tr>";
            foreach ($appointments as $appointment) {
                echo "<tr>
                        <td>" . htmlspecialchars($appointment['doctor_name']) . "</td>
                        <td>" . htmlspecialchars($appointment['clinic_name']) . "</td>
                        <td>" . htmlspecialchars($appointment['appointment_time']) . "</td>
                        <td>" . htmlspecialchars($appointment['appointment_symptoms']) . "</td>
                        <td><button onclick=\"cancelAppointment(" . $appointment['appointment_id'] . ")\">Cancel</button></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You have no appointments.</p>";
        }
        ?>
        <button onclick="location.href='patient.php';">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
    <script>
        function cancelAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                window.location.href = 'cancel_appointment.php?id=' + appointmentId;
            }
        }
    </script>
</body>

</html>
