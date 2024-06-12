<?php
$host = "localhost";  
$db_name = "clinic";  
$username = "root";   
$password = "Antonio1228";       

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>
