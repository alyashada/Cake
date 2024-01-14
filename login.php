<?php
include 'header2.php';
session_start();
require_once 'includes/dbconn.php';

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to the appropriate page based on the role
    if ($_SESSION['role'] == 'Admin') {
        header("Location: indexadmin.php");
        exit();
    } elseif ($_SESSION['role'] == 'Delight') {
        header("Location: indexdelight.php");
        exit();
    } elseif ($_SESSION['role'] == 'Cakies') {
        header("Location: indexcakies.php");
        exit();
    }
}

// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Validate username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the user
    $sql = "SELECT u.id, r.roles_name FROM roles r, user u WHERE u.role_id = r.id AND u.username = ? AND u.password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Set user information in the session
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['roles_name'];

		// Set user_id in the session
        $_SESSION['user_id'] = $_SESSION['id'];

        // Redirect to the appropriate page based on the role
        if ($_SESSION['role'] == 'Admin') {
            header("Location: indexadmin.php");
            exit();
        } elseif ($_SESSION['role'] == 'Delight') {
            header("Location: indexdelight.php");
            exit();
        } elseif ($_SESSION['role'] == 'Cakies') {
            header("Location: indexcakies.php");
            exit();
        }
    } else {
        // Invalid credentials, redirect to login page with an error message
        header('Location: login.php?error=Invalid credentials');
        exit;
    }
}

// Assuming the user just logged in successfully
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    // Transfer items from session cart to database
    if (!empty($_SESSION['cart'][$userId])) {
        foreach ($_SESSION['cart'][$userId] as $itemId => $cartItem) {
            $cart_id = $cartItem['cart_id'];
            $menu_id = $cartItem['menu_id'];
            $quantity = $cartItem['quantity'];

            // Insert into the cart table
            $insert_query = mysqli_prepare($conn, "INSERT INTO cart (menu_id, quantity, cart_id, user_id) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert_query, 'iiis', $menu_id, $quantity, $cart_id, $userId);
            $result = mysqli_stmt_execute($insert_query);

            if (!$result) {
                echo "Error inserting into cart: " . mysqli_error($conn);
            }

            mysqli_stmt_close($insert_query);
        }

        // Clear the session cart only if items are successfully transferred to the database
        if ($result) {
            unset($_SESSION['cart'][$userId]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css>
</head>
<body>

<main class="page-auth">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <section class="auth-wrapper">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h2 class="auth-section-title">Log In</h2>
							<?php
							// Check for error parameter in the URL
							if (isset($_GET['error'])) {
								$errorMessage = $_GET['error'];
								echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
							}
							?>
                            <p class="auth-section-subtitle">Sign in to your account to continue.</p>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="form-group">
                                    <label for="username">Username <sup>*</sup></label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username *">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password <sup>*</sup></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password *">
                                </div>
                                <button type="submit" class="btn btn-primary" name="login">Login</button>
                            </form>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <img src="images/login.png" alt="login" class="img-fluid">
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<!-- Add your JS scripts or CDN links here -->

</body>
</html>

<?php include 'footer.php'; ?>
