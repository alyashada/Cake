<?php include 'headeradmin.php'; 
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle it as appropriate
    header("Location: login.php");
    exit;
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 require_once 'includes/dbconn.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
</head>
<body>
    <br>
	<div class="container">
        <div class="table-container">
            <h1>Menu List</h1>
            <div class="search-form d-flex justify-content-center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="form-inline my-2 my-lg-0">
                <input type="text" class="form-control mr-sm-2" name="search" placeholder="Search Name">
                <button type="submit" class="btn btn-primary mr-2">Search</button>
                <a href="product.php" class="btn btn-primary">Add New Product</a>
            </form>
            <br>
            </div>
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // Set the search term
                    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

                    // Prepare the query with the search term using prepared statements
                    $query = "SELECT * FROM menu WHERE id LIKE ? OR name LIKE ?";
                    $stmt = mysqli_prepare($conn, $query);

                    // Bind parameters
                    $searchParam = "%$searchTerm%";
                    mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);

                    // Execute the statement
                    mysqli_stmt_execute($stmt);

                    // Get the result
                    $result = mysqli_stmt_get_result($stmt);

                    // Loop through the results and display them in the table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><img src='data:image/jpg;base64," . base64_encode($row['image']) . "' alt='Image'  width='200'></td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td> RM" . $row['price'] . "</td>";
                        echo "<td><a href='editproduct.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a></td>";
                        echo "<td><a href='deleteproduct.php?id=" . $row['id'] . "' class='btn btn-danger'>Delete</a></td>";
                        echo "</tr>";
                    }

                    // Close the connection
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
