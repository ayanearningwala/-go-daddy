<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Results</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        button { background-color: #28a745; color: white; padding: 5px 10px; border: none; }
    </style>
</head>
<body>
    <h2>Search Results</h2>
    <table>
        <tr>
            <th>Domain</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php
        $query = $_GET['query'] ?? '';
        $stmt = $conn->prepare("SELECT * FROM domains WHERE name LIKE ? AND available = 1");
        $stmt->execute(["%$query%"]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['name']}{$row['extension']}</td>
                    <td>{$row['price']}</td>
                    <td>
                        <form action='add_to_cart.php' method='POST'>
                            <input type='hidden' name='domain_id' value='{$row['id']}'>
                            <button type='submit'>Add to Cart</button>
                        </form>
                    </td>
                </tr>";
        }
        ?>
    </table>
</body>
</html>
