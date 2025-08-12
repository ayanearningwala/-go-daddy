<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=dby2kvc9g6kdf3", "ujpo5dzfqq5bv", "givgwoqydhjp");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
