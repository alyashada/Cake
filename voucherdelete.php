<?php
// voucherdelete.php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $voucherId = $_GET['id'];

    // Include your database connection code
    include 'includes/dbconn.php';

    // Perform the deletion query
    $sql = "DELETE FROM voucher WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $voucherId);

    if ($stmt->execute()) {
        // Deletion successful
        header("Location: voucher.php");
        exit;
    } else {
        // Error in deletion
        echo "Error deleting voucher.";
    }

    $stmt->close();
    $conn->close();
}
?>
