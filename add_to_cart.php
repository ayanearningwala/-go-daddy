<?php
include 'db.php';

$domain_display = 'No domain selected';

// Function to fetch domain details
function getDomainDetails($conn, $domain_ids) {
    try {
        $placeholders = implode(',', array_fill(0, count($domain_ids), '?'));
        $stmt = $conn->prepare("SELECT name, extension, price FROM domains WHERE id IN ($placeholders)");
        $stmt->execute($domain_ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Check Add to Cart Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['domain_ids']) && is_array($_POST['domain_ids']) && !empty($_POST['domain_ids'])) {
        $domain_ids = array_map('intval', $_POST['domain_ids']);
        $domains = getDomainDetails($conn, $domain_ids);

        if (!empty($domains)) {
            $domain_display = '';
            foreach ($domains as $domain) {
                $domain_display .= htmlspecialchars($domain['name'] . $domain['extension']) . " - $" . htmlspecialchars($domain['price']) . "<br>";
            }
            
            // Add to cart in database
            try {
                foreach ($domain_ids as $id) {
                    $stmt = $conn->prepare("INSERT INTO cart (domain_id) VALUES (?)");
                    $stmt->execute([$id]);
                }
            } catch (PDOException $e) {
                $domain_display = "Error adding to cart: " . $e->getMessage();
            }
        } else {
            $domain_display = "Error: Selected domains not found!";
        }
    } else {
        $domain_display = "Error: No domains selected!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add to Cart</title>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-box h2 {
            margin-bottom: 20px;
            font-size: 20px;
            color: #4CAF50;
        }

        .modal-details {
            font-size: 18px;
            margin: 15px 0;
            color: #333;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .modal-buttons button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancel-btn {
            background-color: #f44336;
            color: white;
        }

        .ok-btn {
            background-color: #4CAF50;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #d32f2f;
        }

        .ok-btn:hover {
            background-color: #388E3C;
        }
    </style>
    <script>
        function goBack() {
            window.history.back(); // Goes back to the previous page
        }

        function orderSuccess() {
            // Logic for successful order (you can redirect to a success page or show a success message)
            alert("Order Successful!");
            window.location.href = "order_success.php"; // Redirect to a success page
        }

        function closeModal() {
            document.querySelector('.modal-overlay').style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="modal-overlay">
        <div class="modal-box">
            <h2>Added to Cart</h2>
            <div class="modal-details">
                <?php echo $domain_display; ?>
            </div>
            <div class="modal-buttons">
                <button class="cancel-btn" onclick="goBack()">Cancel</button>
                <button class="ok-btn" onclick="orderSuccess()">OK</button>
            </div>
        </div>
    </div>
</body>
</html>
