<?php
// orderdelete.php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Include your database connection code
    include 'includes/dbconn.php';

    // Perform the deletion query
    $sql = "DELETE FROM `order` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);

    if ($stmt->execute()) {
        // Deletion successful
        $message = "Order successfully deleted.";

        // Close the prepared statement
        $stmt->close();
        $conn->close();

        // Embed JavaScript to display alert
        echo '<script>';
        echo 'alert("' . $message . '");';
        echo 'window.location.href = "ordercust.php";'; // Redirect to the desired page
        echo '</script>';
        exit;
    } else {
        // Error in deletion
        $error = "Error deleting order.";

        // Close the prepared statement
        $stmt->close();
        $conn->close();

        // Embed JavaScript to display alert
        echo '<script>';
        echo 'alert("' . $error . '");';
        echo 'window.location.href = "ordercust.php";'; // Redirect to the desired page
        echo '</script>';
        exit;
    }
}
?>
