<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get orders from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT o.id, o.order_date, o.total_amount, o.delivery_date, o.status, o.reason, p.name, p.image, oi.quantity
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM | Orders</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f3f6;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #212121;
        }

        .no-orders {
            text-align: center;
            color: #777;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Order Item Styles */
        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .order-item img {
            max-width: 150px;
            margin-right: 20px;
        }

        .order-details {
            flex-grow: 1;
        }

        .order-details h3 {
            margin: 0 0 10px;
            color: #212121;
        }

        .order-details p {
            margin: 0 0 10px;
            color: #555;
        }

        .cancel-btn {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color:#767576;
        }

        /* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* 10% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
    border-radius: 5px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

h3 {
    margin-top: 0;
}

input[type="radio"],
input[type="text"] {
    margin-bottom: 10px;
}

.btn {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #767576;
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="orders">
            <h2>Your Orders</h2>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="order-item">';
                    echo '<img src="products/' . $row['image'] . '" alt="' . $row['name'] . '">';
                    echo '<div class="order-details">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>Quantity: ' . $row['quantity'] . '</p>';
                    echo "<p>Order Date: " . $row['order_date'] . "</p>";
                    if ($row['status'] == 'canceled') {
                        echo "<p>Status: <span style='color:red;'>Canceled</span></p>";
                    } else {
                        echo "<p>Expected Delivery By: " . $row['delivery_date'] . "</p>";
                        echo "<p>Status: " . $row['status'] . "</p>"; // Display delivery date
                        echo '<button class="cancel-btn" onclick="openModal(' . $row['id'] . ')">Cancel</button>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-orders">You haven\'t made any orders yet.</p>';
            }
            ?>

            <!-- Modal for cancellation reasons -->
            <div id="reasonModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Select Reason for Cancellation:</h3>
                    <form id="reasonForm">
                        <input type="radio" id="reason1" name="reason" value="Out of Stock">
                        <label for="reason1">Not Satisfied</label><br>
                        <input type="radio" id="reason2" name="reason" value="Changed Mind">
                        <label for="reason2">I Don't want this item</label><br>
                        <input type="radio" id="reason3" name="reason">
                        <label for="reason3">Enter the reason</label> <br>
                        <input type="text" id="customReason" name="reason" style="display: none;">
                        <button type="submit" class="btn">Submit</button>
                    </form>
                </div>
            </div>

        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        function openModal(orderId) {
            var modal = document.getElementById('reasonModal');
            modal.style.display = 'block';

            var closeBtn = document.getElementsByClassName('close')[0];
            closeBtn.onclick = function () {
                modal.style.display = 'none';
            }

            var form = document.getElementById('reasonForm');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var formData = new FormData(form);
                formData.append('orderId', orderId);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Order Canceled successfully!'); // Show success message
                            modal.style.display = 'none'; // Close modal
                            window.location.href = 'orders.php'; // Redirect to orders.php
                        } else {
                            alert('Error: ' + xhr.status);
                        }
                    }
                };

                xhr.open('POST', 'cancel_order.php', true);
                xhr.send(formData);
            });
        }

        document.getElementById("reason3").addEventListener("change", function() {
            var customReasonInput = document.getElementById("customReason");
            if (this.checked) {
                customReasonInput.style.display = "block";
            } else {
                customReasonInput.style.display = "none";
            }
        });
    </script>
</body>
</html>
