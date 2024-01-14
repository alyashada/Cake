<?php
// Include header and establish database connection
include 'headeradmin.php';
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle it as appropriate
    header("Location: login.php");
    exit;
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/dbconn.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Staff List</title>
</head>
<body>
    <br>
    <div class="container">
        <div class="table-container">
            <h1>Staff List</h1>
            <div class="search-form d-flex justify-content-center">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="form-inline my-2 my-lg-0">
                    <input type="text" class="form-control mr-sm-2" name="searchTerm" placeholder="Search Staff" value="<?php echo isset($_GET['searchTerm']) ? $_GET['searchTerm'] : ''; ?>">
                    <button type="submit" class="btn btn-primary mr-2">Search</button>
                    <a href="addstaff.php" class="btn btn-primary">Add New Staff</a>
                </form>
                <br>
            </div>
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Role Name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve search term
                    $searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

                    // Prepare query with search and role_id filtering
                    $query = "SELECT user.*, roles.roles_name FROM user
                              JOIN roles ON user.role_id = roles.id
                              WHERE user.role_id IN (1) AND user.name LIKE ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "s", $searchParam);

                    // Bind search parameter
                    $searchParam = "%$searchTerm%";
                    mysqli_stmt_execute($stmt);

                    // Fetch results and display
                    $result = mysqli_stmt_get_result($stmt);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['number'] . "</td>";
                        echo "<td>" . $row['roles_name'] . "</td>";
                        echo "<td><a href='staffedit.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a></td>";
                        echo "<td><a href='staffdelete.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a></td>";
                        echo "</tr>";
                    }

                    // Close statement and connection
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
