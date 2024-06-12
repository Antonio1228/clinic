<?php
include 'config.php';
session_start();

$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$clinic_id = isset($_GET['clinic_id']) ? $_GET['clinic_id'] : '';

try {
    $sql = "SELECT r.review_rating, r.review_comment, p.patient_name 
            FROM Reviews r
            JOIN Patients p ON r.review_patient_id = p.patient_id
            WHERE r.review_doctor_id = :doctor_id AND r.review_clinic_id = :clinic_id
            ORDER BY r.review_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
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
        <h1>View Reviews</h1>
    </header>
    <main>
        <?php if (isset($reviews) && !empty($reviews)): ?>
            <table>
                <tr>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Patient Name</th>
                </tr>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['review_rating']); ?></td>
                        <td><?php echo htmlspecialchars($review['review_comment']); ?></td>
                        <td><?php echo htmlspecialchars($review['patient_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No reviews found.</p>
        <?php endif; ?>
        <button onclick="history.back();" class="action-button">Back to Search Results</button>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
