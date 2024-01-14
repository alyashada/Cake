<?php
include 'headeradmin.php';
session_start();
require_once 'includes/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $number = $_POST["number"];
    $rolename = $_POST["rolename"];

    // Sanitize inputs to prevent potential vulnerabilities
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);  // Store plain password
    $name = mysqli_real_escape_string($conn, $name);
    $number = mysqli_real_escape_string($conn, $number);
    $rolename = mysqli_real_escape_string($conn, $rolename);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO `user` (username, password, name, number, role_id) 
                            SELECT ?, ?, ?, ?, id FROM `roles` WHERE roles_name = ?");
    $stmt->bind_param("sssss", $username, $password, $name, $number, $rolename);  // Bind all parameters

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Customer successfully added.";
    } else {
        echo "Error adding customer: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Customer</title>
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

    function togglePassword() {
        var passwordInput = document.getElementById("password");
        var checkbox = document.getElementById("showPassword");

        if (checkbox.checked) {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    }

    function validateNumber(event) {
        const key = event.key; // Use event.key for better compatibility

        // Allow only numbers (0-9), backspace, and delete
        if (!/\d|Backspace|Delete/.test(key)) {
            alert("Only numbers are allowed for the 'Number' field.");
            return false;
        }

        return true;
        }

        // Attach the validation function to both keypress and paste events
        document.getElementById("number").addEventListener("keypress", validateNumber);
        document.getElementById("number").addEventListener("paste", validateNumber);

</script>
</head>
<body>
    <div class="container">
        <h1>Add Customer</h1>
        <div class="form-container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validatePassword();">
                <div class="form-group">
                    <label for="username">Username: </label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
                </div>

                <div class="form-group">
                    <label for="name">Name: </label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="number">Number: </label>
                    <input type="number" class="form-control" id="number" name="number" placeholder="Number" required onkeypress="return validateNumber(event);">
                </div>
                <div class="form-group">
                    <label for="rolename">Role Name: </label>
                    <select class="form-control" id="rolename" name="rolename" required>
                        <option value="Cakies">Cakies</option>
                        <option value="Delight">Delight</option>
                    </select>
                </div>
                <input class="btn btn-primary" type="submit" value="Add Customer">
                <button class="btn btn-danger" onclick="location.href='customer.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
