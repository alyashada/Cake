<?php
include 'header3.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
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

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title> Profile </title>
        
        <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <div>
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Number:</strong> <?php echo $user['number']; ?></p>
            <p><strong>Number:</strong> <?php echo $user['address']; ?></p>
            <p><strong>Role:</strong> <?php echo $roleName; ?></p>
            <a href="profileedit.php" class="btn btn-primary">Edit Profile</a>

        </div>
    </div>
    <!-- Include your JS scripts or CDN links here -->
</body>
</html>


<?php
include 'footer.php';
?>
