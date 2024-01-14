<?php
// logout.php

// Start or resume a session
session_start();

// Include the file with database connection information
include 'includes/dbconn.php';

// Assuming the user just logged out
if (isset($_SESSION['id'])) {
    // Transfer items from the database to the session cart
    $userId = $_SESSION['id'];

    if ($userId) {
        $sql = "SELECT * FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $menu_id = $row['menu_id'];
                $quantity = $row['quantity'];
                $cart_id = $row['cart_id'];

                // Add item to the session cart
                $_SESSION['cart'][$userId][$menu_id] = array(
                    'cart_id' => $cart_id,
                    'menu_id' => $menu_id,
                    'quantity' => $quantity
                );
            }

            // Clear the cart in the database after transferring to the session
            $stmtDeleteCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmtDeleteCart->bind_param("i", $userId);
            $stmtDeleteCart->execute();
            $stmtDeleteCart->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redirect the user to the login page or any other appropriate page
header("Location: login.php");
exit();
?>
