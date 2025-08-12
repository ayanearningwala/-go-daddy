<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        button { background-color: #dc3545; color: white; padding: 5px 10px; border: none; }
    </style>
</head>
<body>
    <h2>Your Cart</h2>
    <table>
        <tr>
            <th>Domain</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php
        $stmt = $conn->prepare("SELECT cart.id, domains.name, domains.extension, domains.price 
                                FROM cart JOIN domains ON cart.domain_id = domains.id");
        $stmt->execute();
        $total = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total += $row['price'];
            echo "<tr>
                    <td>{$row['name']}{$row['extension']}</td>
                    <td>{$row['price']}</td>
                    <td>
                        <form action='remove_from_cart.php' method='POST'>
                            <input type='hidden' name='cart_id' value='{$row['id']}'>
                            <button type='submit'>Remove</button>
                        </form>
                    </td>
                </tr>";
        }
        ?>
    </table>
    <h3>Total: <?php echo $total; ?></h3>
    <form action="checkout.php" method="POST">
        <button type="submit">Checkout</button>
    </form>
</body>
</html>
