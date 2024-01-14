<?php
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
    <title>Order List</title>
</head>
<body>
    <br>
    <div class="container">
        <div class="table-container">
            <h1>Your Order List</h1>
            <div class="search-form d-flex justify-content-center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="form-inline my-2 my-lg-0">
                <label for="searchTerm" class="sr-only">Search Your Order</label>
                <input type="text" id="searchTerm" class="form-control mr-sm-2" name="searchTerm" placeholder="Search by User ID" value="<?php echo isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : ''; ?>">
                <button type="submit" class="btn btn-primary mr-2">Search</button>
            </form>
                <br>
            </div>
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Total Payment (RM)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Retrieve search term
                    $searchTerm = isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : '';

                    // Fetch user orders from the database
                    if (!empty($searchTerm)) {
                        $sqlUserOrders = "SELECT * FROM orders WHERE user_id = ?";
                        $stmtUserOrders = $conn->prepare($sqlUserOrders);
                        $stmtUserOrders->bind_param("s", $searchTerm); // Assuming 'user_id' is a string, change the data type accordingly if it's different
                        $stmtUserOrders->execute();
                        $resultUserOrders = $stmtUserOrders->get_result();

                        // Display user orders in a table
                        if ($resultUserOrders->num_rows > 0) {
                            while ($rowOrder = $resultUserOrders->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td><a href="orderview.php?order_id=' . htmlspecialchars($rowOrder['id']) . '">' . htmlspecialchars($rowOrder['id']) . '</a></td>';
                                echo '<td><a href="customer.php?user_id=' . htmlspecialchars($rowOrder['user_id']) . '">' . htmlspecialchars($rowOrder['user_id']) . '</a></td>';
                                echo '<td>RM' . number_format($rowOrder['total_payment'], 2) . '</td>';
                                echo '<td>' . htmlspecialchars($rowOrder['status']) . '</td>';
                                echo '<td><a href="ordercustdelete.php?id=' . htmlspecialchars($rowOrder['id']) . '" class="btn btn-danger">Delete</a></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">No orders found for User ID: ' . htmlspecialchars($searchTerm) . '.</td></tr>';
                        }
                    } else {
                        $sqlUserOrders = "SELECT * FROM orders";
                        $stmtUserOrders = $conn->prepare($sqlUserOrders);
                        $stmtUserOrders->execute();
                        $resultUserOrders = $stmtUserOrders->get_result();

                        // Display all user orders in a table
                        if ($resultUserOrders->num_rows > 0) {
                            while ($rowOrder = $resultUserOrders->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td><a href="orderview.php?order_id=' . htmlspecialchars($rowOrder['id']) . '">' . htmlspecialchars($rowOrder['id']) . '</a></td>';
                                echo '<td><a href="customer.php?user_id=' . htmlspecialchars($rowOrder['user_id']) . '">' . htmlspecialchars($rowOrder['user_id']) . '</a></td>';
                                echo '<td>RM' . number_format($rowOrder['total_payment'], 2) . '</td>';
                                echo '<td>' . htmlspecialchars($rowOrder['status']) . '</td>';
                                echo '<td><a href="ordercustdelete.php?id=' . htmlspecialchars($rowOrder['id']) . '" class="btn btn-danger">Delete</a></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">No orders found.</td></tr>';
                        }
                    }

                    // Close the statement
                    if (isset($stmtUserOrders)) {
                        $stmtUserOrders->close();
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

