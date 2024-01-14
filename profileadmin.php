<?php
include 'headeradmin.php';

session_start();

// Check database connection
include 'includes/dbconn.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Create the dashboard



echo "<div class='container'>";
echo "<h1>User Profile</h1>";
echo "<div>";
echo "<p><strong>Username:</strong> " . $user['username'] . "</p>";
echo "<p><strong>Name:</strong> " . $user['name'] . "</p>";
echo "<p><strong>Number:</strong> " . $user['number'] . "</p>";
echo "<p><strong>Role:</strong> " . $roleName . "</p>";
echo "</div>";
echo "</div>";

// Close the database connection
$conn->close();

// Include footer.php
echo "<br>";
include 'footer.php';
?>
