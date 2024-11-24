<?php
$host = 'localhost';  // MySQL server
$dbname = 'event';      // Database name
$username = 'root';  // Database username
$password = '';       // Database password (if empty, ensure the user has no password)

try {
    // Creating the PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test the connection by executing a simple query
    $stmt = $pdo->query('SELECT 1');
    echo "Connection successful!";
} catch (PDOException $e) {
    // If the connection fails, output the error message
    die("Connection failed: " . $e->getMessage());
}
?>
