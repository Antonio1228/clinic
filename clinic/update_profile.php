<?php
include 'config.php';
session_start();

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login_doctor.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_name'], $_POST['doctor_gender'], $_POST['doctor_profession'], $_POST['clinic_id'], $_POST['doctor_password'])) {
    $doctor_name = $_POST['doctor_name'];
    $doctor_gender = $_POST['doctor_gender'];
    $doctor_profession = $_POST['doctor_profession'];
    $clinic_id = $_POST['clinic_id'];
    $doctor_password = $_POST['doctor_password'];

    try {
        $sql = "SELECT doctor_password FROM Doctors WHERE doctor_id = :doctor_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->execute();
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor && password_verify($doctor_password, $doctor['doctor_password'])) {
            $sql = "UPDATE Doctors SET doctor_name = :doctor_name, doctor_gender = :doctor_gender, doctor_profession = :doctor_profession, clinic_id = :clinic_id WHERE doctor_id = :doctor_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':doctor_name', $doctor_name, PDO::PARAM_STR);
            $stmt->bindParam(':doctor_gender', $doctor_gender, PDO::PARAM_STR);
            $stmt->bindParam(':doctor_profession', $doctor_profession, PDO::PARAM_STR);
            $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
            $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
            $stmt->execute();
            $message = "Profile successfully updated!";
        } else {
            $message = "Invalid password. Please try again.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

try {
    $sql = "SELECT doctor_name, doctor_gender, doctor_profession, clinic_id FROM Doctors WHERE doctor_id = :doctor_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Personal Information</title>
    <link rel="stylesheet" href="register_styles.css">
</head>

<body>
    <header>
        <h1>Update Personal Information</h1>
    </header>
    <main>
        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="update_profile.php" method="POST" class="form-container">
            <div class="input-group">
                <label for="doctor_name">Name</label>
                <input type="text" id="doctor_name" name="doctor_name"
                    value="<?php echo htmlspecialchars($doctor['doctor_name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="doctor_gender">Gender</label>
                <input type="text" id="doctor_gender" name="doctor_gender"
                    value="<?php echo htmlspecialchars($doctor['doctor_gender']); ?>">
            </div>
            <div class="input-group">
                <label for="doctor_profession">Profession</label>
                <input type="text" id="doctor_profession" name="doctor_profession"
                    value="<?php echo htmlspecialchars($doctor['doctor_profession']); ?>">
            </div>
            <div class="input-group">
                <label for="doctor_password">Password (Required for Submission)</label>
                <input type="password" id="doctor_password" name="doctor_password" required>
            </div>
            <button type="submit" class="register-button">Update</button>
        </form>
        <button onclick="location.href='doctor.html';" class="register-button">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
