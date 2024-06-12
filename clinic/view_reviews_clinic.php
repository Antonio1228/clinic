<?php
session_start();
include 'config.php';

if (!isset($_SESSION['clinic_id'])) {
    header("Location: login_clinic.php");
    exit();
}

$clinic_id = $_SESSION['clinic_id'];

try {
    $sql = "SELECT r.review_rating, r.review_comment, p.patient_name, d.doctor_name 
            FROM Reviews r
            JOIN Patients p ON r.review_patient_id = p.patient_id
            JOIN Doctors d ON r.review_doctor_id = d.doctor_id
            WHERE r.review_clinic_id = :clinic_id
            ORDER BY r.review_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':clinic_id', $clinic_id, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "錯誤: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Reviews</title>
    <link rel="stylesheet" href="doctor_reviews.css">
</head>

<body>
    <header>
        <h1>Your Reviews</h1>
    </header>
    <main>
        <?php if (isset($reviews) && !empty($reviews)): ?>
            <table>
                <tr>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Doctor Name</th>
                </tr>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['review_rating']); ?></td>
                        <td><?php echo htmlspecialchars($review['review_comment']); ?></td>
                        <td><?php echo htmlspecialchars($review['doctor_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No reviews found.</p>
        <?php endif; ?>
        <button onclick="location.href='clinic_dashboard.php';" class="action-button">Back to Dashboard</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
