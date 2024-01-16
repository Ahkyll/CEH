<?php
// Replace these values with your actual database credentials
$host = 'localhost';
$dbname = 'ceh1';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    // Other PDO configurations can be added if needed
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
