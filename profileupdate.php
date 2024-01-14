<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: profile.php'); // Redirect to the login page if not logged in
    exit;
}

require_once 'includes/dbconn.php';


// Fetch user details from the database
$userId = $_SESSION['id'];
$sqlUser = "SELECT * FROM user WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];  
    $name = $_POST['name'];          
    $number = $_POST['number'];

    // Update the user details in the database
    $sql = "UPDATE user SET username = ?, name = ?, number = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $name, $number, $userId);

    if ($stmt->execute()) {
        // User profile updated successfully
        header("Location: profile.php");
        exit;
    } else {
        // Error in updating user profile
        $em = "Error updating profile.";
    }

    $stmt->close();
}

?>