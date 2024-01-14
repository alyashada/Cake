<?php
// staffdelete.php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Include your database connection code
    include 'includes/dbconn.php';

    // Perform the deletion query
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Deletion successful
        header("Location: staff.php");
        exit;
    } else {
        // Error in deletion
        echo "Error deleting staff.";
    }

    $stmt->close();
    $conn->close();
}
?>
