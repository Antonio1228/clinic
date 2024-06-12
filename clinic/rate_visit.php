<?php
include 'config.php'; 
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.html"); 
    exit();
}

$patient_id = $_SESSION['patient_id'];

try {
    $sql = "SELECT Appointments.appointment_id, Doctors.doctor_name, Clinics.clinic_name, Appointments.appointment_time 
            FROM Appointments 
            JOIN Doctors ON Appointments.appointment_doctor_id = Doctors.doctor_id
            JOIN Clinics ON Appointments.appointment_clinic_id = Clinics.clinic_id
            WHERE Appointments.appointment_patient_id = :patient_id AND Appointments.appointment_doctor_id = Doctors.doctor_id
            ORDER BY Appointments.appointment_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_STR);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['rating'], $_POST['comment'])) {
    $appointment_id = $_POST['appointment_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    try {
        $sql = "INSERT INTO Reviews (review_patient_id, review_doctor_id, review_clinic_id, review_rating, review_comment)
                SELECT :patient_id, appointment_doctor_id, appointment_clinic_id, :rating, :comment
                FROM Appointments
                WHERE appointment_id = :appointment_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->execute();
        $message = "Review successfully submitted!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rate a Visit</title>
    <link rel="stylesheet" href="rate.css">
</head>

<body>
    <header>
        <h1>Rate a Visit</h1>
    </header>
    <main>
        <?php
        if (isset($message)) {
            echo "<p style='color: green;'>$message</p>";
        }
        ?>
        <?php
        if ($appointments) {
            echo "<table>";
            echo "<tr><th>Doctor Name</th><th>Clinic Name</th><th>Appointment Time</th><th>Action</th></tr>";
            foreach ($appointments as $appointment) {
                echo "<tr>
                        <td>" . htmlspecialchars($appointment['doctor_name']) . "</td>
                        <td>" . htmlspecialchars($appointment['clinic_name']) . "</td>
                        <td>" . htmlspecialchars($appointment['appointment_time']) . "</td>
                        <td>
                            <form method='POST'>
                                <input type='hidden' name='appointment_id' value='" . $appointment['appointment_id'] . "'>
                                <label for='rating'>Rating:</label>
                                <select name='rating' required>
                                    <option value='1'>1</option>
                                    <option value='2'>2</option>
                                    <option value='3'>3</option>
                                    <option value='4'>4</option>
                                    <option value='5'>5</option>
                                </select>
                                <label for='comment'>Comment:</label>
                                <textarea name='comment' required></textarea>
                                <button type='submit'>Submit Review</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You have no appointments to rate.</p>";
        }
        ?>
        <button onclick="location.href='patient.php';">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
