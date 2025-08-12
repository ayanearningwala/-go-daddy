<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Domain Finder</title>
    <style>
        /* General Reset */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Header Styling */
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Search Container */
        .search-container {
            margin: 20px auto;
            width: 50%;
        }

        input[type="text"] {
            width: 75%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            font-size: 16px;
        }

        .suggestions {
            margin: 20px auto;
            text-align: center;
            font-size: 18px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            width: 60%;
        }

        .suggestions h3 {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .suggestions form {
            margin-bottom: 10px;
        }

        .price {
            color: #007bff;
            font-weight: bold;
        }

        .add-to-cart-btn {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-to-cart-btn:hover {
            background-color: #0056b3;
        }

        /* Cart Icon */
        #cart-icon {
            position: fixed;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        #cart-icon span {
            background: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 14px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <header>
        Domain Finder
    </header>

    <!-- Cart Icon -->
    <?php
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) AS cart_count FROM cart");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $cart_count = $result['cart_count'];
    } catch (Exception $e) {
        $cart_count = 0;
    }
    ?>
    <div id="cart-icon">
        <span id="cart-count"><?php echo $cart_count; ?></span>
    </div>

    <!-- Search Form -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="query" placeholder="Search for a domain..." 
                   value="<?php echo isset($_POST['query']) ? htmlspecialchars($_POST['query']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
        $query = htmlspecialchars($_POST['query']);
        echo "<h2>Search Results for: " . $query . "</h2>";
        echo "<table>
                <tr>
                    <th>Domain</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>";
        // Fetch matching domains
        $stmt = $conn->prepare("SELECT * FROM domains WHERE name LIKE ? AND available = 1");
        $stmt->execute(["%$query%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            foreach ($results as $row) {
                echo "<tr>
                        <td>{$row['name']}{$row['extension']}</td>
                        <td>\${$row['price']}</td>
                        <td>
                            <form action='add_to_cart.php' method='POST'>
                                <input type='hidden' name='domain_id' value='{$row['id']}'>
                                <button type='submit' class='add-to-cart-btn'>Add to Cart</button>
                            </form>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='message'>No results found!</td></tr>";
        }
        echo "</table>";

        // Static suggestions with prices and Add to Cart option
        $suggestions = [
            ["domain" => "$query.com", "price" => "12.99"],
            ["domain" => "www.$query.com", "price" => "15.99"],
            ["domain" => "$query.net", "price" => "10.99"],
            ["domain" => "www.$query.net", "price" => "14.99"],
        ];

        echo "<div class='suggestions'>
                <h3>Suggested Domains</h3>";
        foreach ($suggestions as $index => $suggestion) {
            echo "<form action='add_to_cart.php' method='POST'>
                    <span>{$suggestion['domain']} - <span class='price'>\${$suggestion['price']}</span></span>
                    <input type='hidden' name='domain_name' value='{$suggestion['domain']}'>
                    <input type='hidden' name='domain_price' value='{$suggestion['price']}'>
                    <button type='submit' class='add-to-cart-btn'>Add to Cart</button>
                  </form>";
        }
        echo "</div>";
    }
    ?>
</body>
</html>
