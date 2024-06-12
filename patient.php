<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="patient_styles.css">
    <script>
        window.onload = function() {
            <?php
            session_start();
            if (isset($_SESSION['appointment_message'])) {
                echo "alert('" . addslashes($_SESSION['appointment_message']) . "');";
                unset($_SESSION['appointment_message']); 
            }
            ?>
        }
    </script>
</head>

<body>
    <header>
        <h1>Welcome to Patient Dashboard</h1>
    </header>
    <main>
        <section>
            <h2>Search Doctor</h2>
            <form action="search_results.php" method="POST">
                <input type="text" name="search_term" placeholder="Enter doctor's name or specialty">
                <button type="submit">Search</button>
            </form>
        </section>
        <section>
            <h2>Search Clinic</h2>
            <form action="search_results.php" method="POST">
                <input type="text" name="search_term" placeholder="Enter clinic's name or department">
                <button type="submit">Search</button>
            </form>
        </section>
        <section>
            <h2>View Appointments</h2>
            <button onclick="location.href='view_patient.php';">View Appointments</button>
        </section>
        <section>
            <h2>Rate a Visit</h2>
            <button onclick="location.href='rate_visit.php';">Rate Visit</button>
        </section>
        <section>
            <h2>Update Profile</h2>
            <button onclick="location.href='update_patient.php';">Update Profile</button>
        </section>
        <section>
            <h2>Log Out</h2>
            <button onclick="location.href='logout.php';">Log Out</button>
        </section>
    </main>
    <footer>
        <p>Clinic Registration System © 2024</p>
    </footer>
</body>

</html>
