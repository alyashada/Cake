<?php
session_start(); // Start the session

include 'header3.php'; // Include header3.php, make sure it's properly located

include 'includes/dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user details from the database
$userId = $_SESSION['id'];
$sqlUser = "SELECT * FROM user WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $user = $resultUser->fetch_assoc();

    // Fetch role details from the database
    $roleId = $user['role_id'];
    $sqlRole = "SELECT * FROM roles WHERE id = ?";
    $stmtRole = $conn->prepare($sqlRole);
    $stmtRole->bind_param("i", $roleId);
    $stmtRole->execute();
    $resultRole = $stmtRole->get_result();

    if ($resultRole->num_rows > 0) {
        $role = $resultRole->fetch_assoc();
        $roleName = $role['roles_name'];
    } else {
        // Role not found, handle accordingly
        $roleName = "Unknown Role";
    }
} else {
    // User not found, handle accordingly
    echo "User not found!";
    exit;
}

// Check if the payment form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process payment logic goes here

    // Assuming you have a payment gateway integration
    // In a real-world scenario, you would interact with the payment gateway API

    // For demonstration purposes, let's assume the payment is successful
    $paymentSuccessful = true;

    if ($paymentSuccessful) {
        // Clear the cart and claimed voucher from the session after successful payment
        unset($_SESSION['cart'][$userId]);
        unset($_SESSION['claimed_voucher']);

        // Additional logic for order confirmation, updating order status, etc.

        echo '<script>alert("Payment successful! Thank you for your order.");</script>';
        header('Location: index.php'); // Redirect to home page or order confirmation page
        exit;
    } else {
        echo '<script>alert("Payment failed. Please try again.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <h2>Payment</h2>

        <!-- Display order summary or any relevant information -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <!-- You can loop through the items in the cart and display them here -->
            <ul>
                <?php
                if (isset($_SESSION['cart'][$userId]) && !empty($_SESSION['cart'][$userId])) {
                    foreach ($_SESSION['cart'][$userId] as $item) {
                        echo "<li>{$item['quantity']} x {$item['menu_id']}</li>";
                        // You may want to fetch additional information about the menu item from the database
                        // and display it here (e.g., name, price, etc.).
                    }
                } else {
                    echo "<li>No items in the cart</li>";
                }
                ?>
            </ul>
            <!-- Display total amount -->
            <!-- Example: <p>Total Amount: RM<?php // echo number_format($grand_total, 2); ?></p> -->
        </div>

        <!-- Payment Form -->
        <form action="pay.php" method="post">
            <!-- Payment method selection (you can customize based on your available payment methods) -->
            <div class="form-group">
                <label for="payment">Payment Method:</label>
                <select class="form-control" id="payment" name="payment" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="touch_n_go">Touch 'n Go</option>
                    <!-- Add more payment options as needed -->
                </select>
            </div>

            <!-- Additional payment-related fields can be added here -->

            <!-- Submit button -->
            <div class="button-container">
                <button type="submit" class="btn btn-primary">Submit Payment</button>
            </div>
        </form>

    </div>

</body>

</html>

<?php include 'footer.php'; ?>
