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
        header("Location: orderadmin.php?message=" . urlencode($message));
        exit;
    } else {
        // Error in deletion
        $error = "Error deleting order.";
        header("Location: orderadmin.php?error=" . urlencode($error));
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
