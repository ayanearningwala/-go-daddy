<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
    </style>
</head>
<body>
    <h1>Thank You for Your Purchase!</h1>
    <p>Your domains have been reserved successfully.</p>
    <?php
    $conn->exec("DELETE FROM cart"); // Clear the cart after checkout
    ?>
</body>
</html>
