<?php
include 'config.php';
session_start();

$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

try {
    $sql = "SELECT Doctors.doctor_id, Doctors.doctor_name, Clinics.clinic_id, Clinics.clinic_name, Clinics.clinic_open_time 
            FROM Doctors 
            JOIN Clinics ON Doctors.clinic_id = Clinics.clinic_id
            WHERE Doctors.doctor_name = :search_term OR Clinics.clinic_name = :search_term";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':search_term', $search_term, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results and Book Appointment</title>
    <link rel="stylesheet" href="results_styles.css">
</head>
<body>
    <header>
        <h1>Search Results</h1>
    </header>
    <main>
        <?php
        if ($results) {
            echo "<table>";
            echo "<tr><th>Doctor Name</th><th>Clinic Name</th><th>Clinic Open Time</th><th>Action</th></tr>";
            foreach ($results as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['doctor_name']) . "</td>
                        <td>" . htmlspecialchars($row['clinic_name']) . "</td>
                        <td>" . htmlspecialchars($row['clinic_open_time']) . "</td>
                        <td>
                            <button onclick=\"bookAppointment(" . $row['doctor_id'] . ", " . $row['clinic_id'] . ")\">Book Appointment</button>
                            <button onclick=\"location.href='view_reviews_search.php?doctor_id=" . $row['doctor_id'] . "&clinic_id=" . $row['clinic_id'] . "';\">View Reviews</button>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found.</p>";
        }
        ?>

        <div id="appointmentForm" style="display:none;">
            <h2>Book an Appointment</h2>
            <form action="book_appointment.php" method="POST">
                <input type="hidden" name="doctor_id" id="doctorId">
                <input type="hidden" name="clinic_id" id="clinicId">
                <label for="appointment_time">Appointment Time:</label>
                <input type="datetime-local" name="appointment_time" min="<?php echo date('Y-m-d\TH:i'); ?>" required>
                <label for="symptoms">Symptoms:</label>
                <textarea name="symptoms" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>
        <button onclick="location.href='patient.php';" class="action-button">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
    <script>
        function bookAppointment(doctorId, clinicId) {
            document.getElementById('doctorId').value = doctorId;
            document.getElementById('clinicId').value = clinicId;
            document.getElementById('appointmentForm').style.display = 'block';
        }
    </script>
</body>
</html>
<?php
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
