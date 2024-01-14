<?php include 'header2.php'; ?>
<?php require_once 'includes/dbconn.php'; ?>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    // Check if the required fields are set
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['role']) &&
        isset($_POST['name']) &&
        isset($_POST['number'])
    ) {
        // Get user inputs
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];
        $role_id = $_POST['role'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);

        // Proceed with signup
        $insertQuery = "INSERT INTO user (username, password, role_id, name, number) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssiss", $username, $password, $role_id, $name, $number);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Close the statement
                mysqli_stmt_close($stmt);
                // Set session variable to indicate successful registration
				
				// Redirect to login page
				header("Location: login.php");
                exit();
            } else {
                // Error in executing the statement
                echo "error|Failed to execute the statement";
            }
        } else {
            // Error in preparing the statement
            echo "error|Failed to prepare the statement";
        }

        // Close database connection (this should be outside the if statement)
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
	<link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <section class="auth-wrapper">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h2 class="auth-section-title">Sign Up</h2>
                            <p class="auth-section-subtitle">Create a new account to get started.</p>

                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                                <div class="form-group">
                                    <label for="username">Username <sup>*</sup></label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username *" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password <sup>*</sup></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password *" required>
                                </div>

								<div class="form-group">
									<label for="role">Role:</label>
									<select class="form-control" id="role" name="role" aria-label=".form-select-sm example">
										<option selected>Choose Role</option>
										<option value="2">Delight</option>
										<option value="3">Cakies</option>
									</select>
								</div>

                                <div class="form-group">
                                    <label for="name">Name <sup>*</sup></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name *" required>
                                </div>

                                <div class="form-group">
                                    <label for="number">Number <sup>*</sup></label>
                                    <input type="text" class="form-control" id="number" name="number" placeholder="Number *" required>
                                </div>

                                <input type="submit" class="btn btn-primary" name="paysignup"  onclick="setRegistrationSuccess();" value="Sign Up">
                            </form>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <img src="images/signup.png" alt="signup.png" class="img-fluid">
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
	<script>
		function setRegistrationSuccess() {
			// Use JavaScript to set a cookie or perform an AJAX request to notify the server about the successful registration
			// Here, we're using a simple JavaScript alert as an example
			alert("Registration successful! Please log in.");

			// Optionally, you can also redirect the user to the login page after showing the alert
			window.location.href = "login.php";
		}
	</script>

</body>
</html>

<?php include 'footer.php'; ?>
