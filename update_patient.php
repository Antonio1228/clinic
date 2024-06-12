<?php
include 'config.php'; 
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.html"); 
    exit();
}

$patient_id = $_SESSION['patient_id'];

try {
    $sql = "SELECT patient_name, patient_username, patient_email, patient_gender, patient_birth_date, patient_age, patient_phone, patient_address FROM Patients WHERE patient_id = :patient_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_STR);
    $stmt->execute();
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patient_name'], $_POST['patient_username'], $_POST['patient_email'], $_POST['patient_gender'], $_POST['patient_birth_date'], $_POST['patient_age'], $_POST['patient_phone'], $_POST['patient_address'], $_POST['patient_password'])) {
    $patient_name = $_POST['patient_name'];
    $patient_username = $_POST['patient_username'];
    $patient_email = $_POST['patient_email'];
    $patient_gender = $_POST['patient_gender'];
    $patient_birth_date = $_POST['patient_birth_date'];
    $patient_age = $_POST['patient_age'];
    $patient_phone = $_POST['patient_phone'];
    $patient_address = $_POST['patient_address'];
    $patient_password = $_POST['patient_password'];

    if (!empty($patient_password)) {
        $hashed_password = password_hash($patient_password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $patient['patient_password'];
    }

    try {
        $sql = "UPDATE Patients SET patient_name = :patient_name, patient_username = :patient_username, patient_email = :patient_email, patient_gender = :patient_gender, patient_birth_date = :patient_birth_date, patient_age = :patient_age, patient_phone = :patient_phone, patient_address = :patient_address, patient_password = :patient_password WHERE patient_id = :patient_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $stmt->bindParam(':patient_username', $patient_username, PDO::PARAM_STR);
        $stmt->bindParam(':patient_email', $patient_email, PDO::PARAM_STR);
        $stmt->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        $stmt->bindParam(':patient_birth_date', $patient_birth_date, PDO::PARAM_STR);
        $stmt->bindParam(':patient_age', $patient_age, PDO::PARAM_INT);
        $stmt->bindParam(':patient_phone', $patient_phone, PDO::PARAM_STR);
        $stmt->bindParam(':patient_address', $patient_address, PDO::PARAM_STR);
        $stmt->bindParam(':patient_password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_STR);
        $stmt->execute();
        $message = "Profile successfully updated!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="register_styles.css">
</head>

<body>
    <header>
        <h1>Update Profile</h1>
    </header>
    <main class="form-container">
        <?php
        if (isset($message)) {
            echo "<p style='color: green;'>$message</p>";
        }
        ?>
        <form method="POST">
            <div class="input-group">
                <label for="patient_name">Name:</label>
                <input type="text" name="patient_name" value="<?php echo htmlspecialchars($patient['patient_name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_username">Username:</label>
                <input type="text" name="patient_username" value="<?php echo htmlspecialchars($patient['patient_username']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_email">Email:</label>
                <input type="email" name="patient_email" value="<?php echo htmlspecialchars($patient['patient_email']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_gender">Gender:</label>
                <input type="text" name="patient_gender" value="<?php echo htmlspecialchars($patient['patient_gender']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_birth_date">Birth Date:</label>
                <input type="date" name="patient_birth_date" value="<?php echo htmlspecialchars($patient['patient_birth_date']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_age">Age:</label>
                <input type="number" name="patient_age" value="<?php echo htmlspecialchars($patient['patient_age']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_phone">Phone:</label>
                <input type="text" name="patient_phone" value="<?php echo htmlspecialchars($patient['patient_phone']); ?>" required>
            </div>
            <div class="input-group">
                <label for="patient_address">Address:</label>
                <textarea name="patient_address" required><?php echo htmlspecialchars($patient['patient_address']); ?></textarea>
            </div>
            <div class="input-group">
                <label for="patient_password">New Password (leave blank to keep current password):</label>
                <input type="password" name="patient_password">
            </div>
            <button type="submit" class="register-button">Update Profile</button>
        </form>
        <button onclick="location.href='patient.php';" class="register-button">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
