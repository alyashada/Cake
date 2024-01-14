<?php
include 'headeradmin.php';
session_start();
require_once 'includes/dbconn.php';

// Assuming you have the user ID from the URL
$userId = $_GET['id'];

// Fetch user details from the database
$sql = "SELECT u.username, u.password, u.name, u.number, r.roles_name FROM user u
        JOIN roles r ON u.role_id = r.id WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update the user details in the database
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $rolename = $_POST['rolename'];

        // Assuming roles table has 'id' and 'roles_name' columns
        $sqlUpdate = "UPDATE user SET username = ?, password = ?, name = ?, number = ?, role_id = (SELECT id FROM roles WHERE roles_name = ?) WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sssssi", $username, $password, $name, $number, $rolename, $userId);

        if ($stmtUpdate->execute()) {
            // User updated successfully
            header("Location: customer.php?id=" . $userId);
            exit;
        } else {
            // Error in user update
            $em = "Error updating user.";
        }

        $stmtUpdate->close();
    }
} else {
    // User not found, handle accordingly
    echo "User not found!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;

            // Password must contain at least one uppercase letter, one lowercase letter, and one number
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            if (!regex.test(password)) {
                alert("Password must contain at least one uppercase letter, one lowercase letter, and one number. Minimum length is 8 characters.");
                return false;
            }

            return true;
        }

        function validateNumber(event) {
            const input = event.target;
            const inputValue = input.value;

            // Allow only numbers (0-9), backspace, and delete
            if (!/^\d*$/.test(inputValue)) {
                alert("Only numbers are allowed for the 'Number' field."); // Display popup message
                input.value = inputValue.replace(/[^\d]/g, ''); // Remove non-numeric characters
            }

            return true;
            }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var checkbox = document.getElementById("showPassword");

            passwordInput.type = checkbox.checked ? "text" : "password";
        }

        // Attach the validation function to the input event for both password and number fields
        document.getElementById("password").addEventListener("input", validatePassword);
        document.getElementById("number").addEventListener("input", validateNumber);
    </script>
</head>
<body>
    <div class="container">
        <h1>Edit Customer</h1>
        <div class="form-container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $userId; ?>" onsubmit="return validatePassword();">
                <div class="form-group">
                    <label for="username">Username: </label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password']; ?>" required>
                    <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> Show Password
                </div>
                <div class="form-group">
                    <label for="name">Name: </label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="number">Number: </label>
                    <input type="number" class="form-control" id="number" name="number" value="<?php echo $user['number']; ?>" required oninput="validateNumber(event);">
                </div>
                <div class="form-group">
                    <label for="rolename">Role Name: </label>
                    <select class="form-control" id="rolename" name="rolename" required>
                        <!-- Assuming roles table has 'id' and 'roles_name' columns -->
                        <?php
                        $sqlRoles = "SELECT * FROM roles WHERE roles_name != 'Admin'";
                        $resultRoles = $conn->query($sqlRoles);

                        while ($rowRoles = $resultRoles->fetch_assoc()) {
                            echo '<option value="' . $rowRoles['roles_name'] . '" ' . ($user['roles_name'] == $rowRoles['roles_name'] ? 'selected' : '') . '>' . $rowRoles['roles_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <input class="btn btn-primary" name="submit" type="submit" value="Update Customer">
                <button class="btn btn-danger" onclick="location.href='customer.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
