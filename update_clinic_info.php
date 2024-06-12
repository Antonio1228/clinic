<?php
session_start();
include 'config.php';

if (!isset($_SESSION['clinic_id'])) {
    header("Location: login_clinic.php");
    exit();
}

$clinic_id = $_SESSION['clinic_id'];

try {
    $sql = "SELECT clinic_name, clinic_open_time, clinic_phone, clinic_address, clinic_department FROM Clinics WHERE clinic_id = :clinic_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
    $stmt->execute();
    $clinic = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clinic_name'], $_POST['clinic_open_time'], $_POST['clinic_phone'], $_POST['clinic_address'], $_POST['clinic_department'], $_POST['clinic_password'])) {
    $clinic_name = $_POST['clinic_name'];
    $clinic_open_time = $_POST['clinic_open_time'];
    $clinic_phone = $_POST['clinic_phone'];
    $clinic_address = $_POST['clinic_address'];
    $clinic_department = $_POST['clinic_department'];
    $clinic_password = $_POST['clinic_password'];

    if (!empty($clinic_password)) {
        $hashed_password = password_hash($clinic_password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $clinic['clinic_password'];
    }

    try {
        $sql = "UPDATE Clinics SET clinic_name = :clinic_name, clinic_open_time = :clinic_open_time, clinic_phone = :clinic_phone, clinic_address = :clinic_address, clinic_department = :clinic_department, clinic_password = :clinic_password WHERE clinic_id = :clinic_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':clinic_name', $clinic_name, PDO::PARAM_STR);
        $stmt->bindParam(':clinic_open_time', $clinic_open_time, PDO::PARAM_STR);
        $stmt->bindParam(':clinic_phone', $clinic_phone, PDO::PARAM_STR);
        $stmt->bindParam(':clinic_address', $clinic_address, PDO::PARAM_STR);
        $stmt->bindParam(':clinic_department', $clinic_department, PDO::PARAM_STR);
        $stmt->bindParam(':clinic_password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
        $stmt->execute();
        $message = "Information successfully updated!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Clinic Information</title>
    <link rel="stylesheet" href="update_clinic.css">
</head>

<body>
    <header>
        <h1>Update Clinic Information</h1>
    </header>
    <main class="form-container">
        <?php
        if (isset($message)) {
            echo "<p style='color: green;'>$message</p>";
        }
        ?>
        <form method="POST">
            <div class="input-group">
                <label for="clinic_name">Clinic Name:</label>
                <input type="text" name="clinic_name" value="<?php echo htmlspecialchars($clinic['clinic_name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="clinic_open_time">Open Time:</label>
                <input type="text" name="clinic_open_time" value="<?php echo htmlspecialchars($clinic['clinic_open_time']); ?>" required>
            </div>
            <div class="input-group">
                <label for="clinic_phone">Phone:</label>
                <input type="text" name="clinic_phone" value="<?php echo htmlspecialchars($clinic['clinic_phone']); ?>" required>
            </div>
            <div class="input-group">
                <label for="clinic_address">Address:</label>
                <textarea name="clinic_address" required><?php echo htmlspecialchars($clinic['clinic_address']); ?></textarea>
            </div>
            <div class="input-group">
                <label for="clinic_department">Department:</label>
                <input type="text" name="clinic_department" value="<?php echo htmlspecialchars($clinic['clinic_department']); ?>" required>
            </div>
            <div class="input-group">
                <label for="clinic_password">New Password (leave blank to keep current password):</label>
                <input type="password" name="clinic_password">
            </div>
            <button type="submit" class="register-button">Update Information</button>
        </form>
        <button onclick="location.href='clinic_dashboard.php';" class="register-button">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
