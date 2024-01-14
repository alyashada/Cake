<?php
// Include header and start session
include 'header3.php';
session_start();
include 'includes/dbconn.php';

// Handle voucher claiming
if (isset($_POST['useVoucher']) && isset($_POST['voucher'])) {
    $claimedVoucher = $_POST['voucher'];

    // Validate if the claimed voucher belongs to the user
    $sqlValidateVoucher = "SELECT * FROM claimed_voucher WHERE user_id = ? AND voucher_code = ?";
    $stmtValidateVoucher = $conn->prepare($sqlValidateVoucher);
    $stmtValidateVoucher->bind_param('is', $userId, $claimedVoucher);

    if ($stmtValidateVoucher->execute()) {
        $resultValidateVoucher = $stmtValidateVoucher->get_result();

        if ($resultValidateVoucher->num_rows > 0) {
            // Valid voucher, store it in the session
            $_SESSION['claimed_voucher'] = $claimedVoucher;
        } else {
            echo '<script>alert("Invalid voucher selected.");</script>';
        }
    } else {
        echo '<script>alert("Error validating voucher.");</script>';
    }
}

// Initialize variables for discount and voucher amount
$discountAmount = 0;
$voucherAmount = 0;

// Initialize $grand_total
$grand_total = 0.0;

$claimedVoucher = isset($_SESSION['claimed_voucher']) ? $_SESSION['claimed_voucher'] : null;


// Deduct voucher amount from the grand total
if (isset($_POST['voucher']) && !empty($_POST['voucher'])) {
    $claimedVoucher = $_POST['voucher'];

    if ($claimedVoucher === "CAKE20") {
        $discountPercentage = 20;
        $discountAmount = ($grand_total * $discountPercentage) / 100;
        $grand_total -= $discountAmount;

        echo "<p>Voucher 'CAKE20' Applied: 20% Discount (RM" . number_format($discountAmount, 2) . ")</p>";
    } else {
        $sqlVoucherAmount = "SELECT amount FROM claimed_voucher WHERE user_id = ? AND voucher_code = ?";
        $stmtVoucherAmount = $conn->prepare($sqlVoucherAmount);
        $stmtVoucherAmount->bind_param('is', $userId, $claimedVoucher);
        $stmtVoucherAmount->execute();

        if ($stmtVoucherAmount->error) {
            // Handle the error gracefully
            echo "Error retrieving voucher amount: " . $stmtVoucherAmount->error;
        } else {
            $resultVoucherAmount = $stmtVoucherAmount->get_result();

            if ($resultVoucherAmount->num_rows > 0) {
                $voucherAmount = $resultVoucherAmount->fetch_assoc()['amount'];
                $grand_total -= $voucherAmount;

                echo "<p>Voucher Amount Deducted: RM" . number_format($voucherAmount, 2) . "</p>";
            }
        }
    }

    $_SESSION['claimed_voucher'] = $claimedVoucher;
}

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

        // After validating and storing the voucher
        $_SESSION['claimed_voucher'] = $claimedVoucher;

        // Handle voucher claiming
        if (isset($_POST['useVoucher']) && isset($_POST['voucher'])) {
            $claimedVoucher = $_POST['voucher'];

            // Validate if the claimed voucher belongs to the user
            $sqlValidateVoucher = "SELECT * FROM claimed_voucher WHERE user_id = ? AND voucher_code = ?";
            $stmtValidateVoucher = $conn->prepare($sqlValidateVoucher);
            $stmtValidateVoucher->bind_param('is', $userId, $claimedVoucher);

            if ($stmtValidateVoucher->execute()) {
                $resultValidateVoucher = $stmtValidateVoucher->get_result();

                if ($resultValidateVoucher->num_rows > 0) {
                    // Valid voucher, store it in the session
                    $_SESSION['claimed_voucher'] = $claimedVoucher;
                } else {
                    echo '<script>alert("Invalid voucher selected.");</script>';
                }
            } else {
                echo '<script>alert("Error validating voucher.");</script>';
            }
        }

            // Initialize $grand_total
            $grand_total = 0.0;

            function getClaimedVouchers($userId) {
                // Replace this with your actual logic to retrieve claimed vouchers for the user
                // This is just a placeholder function
                // In a real application, you might fetch this information from a database or another source
                $claimedVouchers = [];

                // Example: Fetch vouchers for the user with $userId from a hypothetical database
                // $claimedVouchers = YourDatabaseClass::getClaimedVouchersByUserId($userId);

                return $claimedVouchers;
            }

            // Assuming you have a function to retrieve claimed vouchers for the user with role_id 3
            $claimed_vouchers = getClaimedVouchers($userId); // Replace $user_id with the actual user ID

            // Display the dropdown with claimed vouchers for role_id 3


            ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<style>

    .button-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .button-container a {
        width: 20%;
    }
</style>

<body>

    <div class="container">
        <h2>Checkout</h2>
        <br>
        <form action="ordercust.php" method="post" id="checkoutForm">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price (RM)</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve and display cart items
                    $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

                        if ($userId && isset($_SESSION['cart'][$userId]) && is_array($_SESSION['cart'][$userId])) {
                            $cartItems = $_SESSION['cart'][$userId];
                            $cartItemIds = array_keys($cartItems);
                            $placeholders = implode(',', array_fill(0, count($cartItemIds), '?'));

                            $sql = "SELECT * FROM menu WHERE id IN ({$placeholders})";
                            $stmt = $conn->prepare($sql);

                            if ($stmt) {
                                $stmt->bind_param(str_repeat('i', count($cartItemIds)), ...$cartItemIds);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $counter = 1;
                                $grand_total = 0.0;
                                $product_names = []; // Initialize an empty array to store product names

                                while ($row = $result->fetch_assoc()) {
                                    $itemId = $row['id'];
                                    $update_value = isset($_POST['update_quantity'][$itemId]) ? $_POST['update_quantity'][$itemId] : $_SESSION['cart'][$userId][$itemId]['quantity'];
                                    $sub_total = floatval($update_value) * floatval($row['price']);
                                    $grand_total += $sub_total;
                                
                                    // Construct the product names array
                                    $product_names[] = $row['name'] . ' (' . $update_value . ')';
                                
                                    echo "<tr>";
                                    echo "<td>{$counter}</td>";
                                    echo "<td><img alt='Image' width='200' src='images/{$row['image']}'></td>";
                                    echo "<td>{$row['name']} ({$update_value})</td>"; // This line is modified to simplify the output
                                    echo "<td>RM{$row['price']}</td>";
                                    echo "<td>{$update_value}</td>";
                                    echo "<td>RM" . number_format($sub_total, 2) . "</td>";
                                    echo "</tr>";
                                    
                                    $counter++;
                                }                                

                                // Assuming you have $grand_total defined before this code block
                                $voucher_discount = 0;
                                $selectedVoucherCode = "No voucher selected";

                                // Check if the form is submitted and the selected voucher code is set
                                if (isset($_GET['voucherDropdown'])) {
                                    $selectedVoucherCode = $_GET['voucherDropdown'];

                                    if ($selectedVoucherCode == "CAKE20") {
                                        // Calculate voucher discount if voucher code is "CAKE20"
                                        $voucher_discount = $grand_total * 0.2;
                                    } elseif ($selectedVoucherCode == "CAKE10") {
                                        // Calculate voucher discount if voucher code is "CAKE10"
                                        $voucher_discount = $grand_total * 0.1;
                                    } elseif ($selectedVoucherCode == "CAKE5") {
                                        // Calculate voucher discount if voucher code is "CAKE5"
                                        $voucher_discount = $grand_total * 0.05;
                                    } elseif ($selectedVoucherCode == "OFFRM10") {
                                        // Fixed voucher discount of RM10
                                        $voucher_discount = 10;
                                    } elseif ($selectedVoucherCode == "OFFRM5") {
                                        // Fixed voucher discount of RM5
                                        $voucher_discount = 5;
                                    } elseif ($selectedVoucherCode == "OFFRM25") {
                                        // Fixed voucher discount of RM25
                                        $voucher_discount = 25;
                                    }

                                }

                                $membership_discount = 0;

                                if ($roleId = 2) {
                                    // Customer gets RM 10 discount for every purchase up to RM 70
                                    // and RM 25 discount for every purchase up to RM 150
                                    if ($grand_total <= 70) {
                                        $membership_discount = 10;
                                    } elseif ($grand_total <= 150) {
                                        $membership_discount = 25;
                                    }
                                } elseif ($roleId = 3) {
                                    // Customer gets RM 35 discount for a purchase of RM 150 or more
                                    // and RM 15 discount for a purchase of RM 65 or more
                                    if ($grand_total >= 150) {
                                        $membership_discount = 35;
                                    } elseif ($grand_total >= 65) {
                                        $membership_discount = 15;
                                    }
                                }

                                // Display or use $membership_discount as needed
                                $total_after_discount = $grand_total - $voucher_discount - $membership_discount;

                                // Display user information based on the selected delivery method
                                if (isset($_GET['delivery'])) {
                                    $deliveryMethod = $_GET['delivery'];

                                    echo '<div class="user-information">';
                                    echo '<h4>Information</h4>';
                                    
                                    // Display user information if available
                                    if (!empty($user)) {
                                        echo '<strong><p>Name:</strong> ' . $user['name'] . '</p>';
                                        echo '<strong><p>Phone Number:</strong> ' . $user['number'] . '</p>';
                                        
                                        if ($deliveryMethod === 'pickup') {
                                            echo '<strong><p>Note:</strong> Please do pickup at the store.</p>';
                                        } elseif ($deliveryMethod === 'delivery') {
                                            echo '<strong><p>Address:</strong> ' . $user['address'] . '</p>';
                                        }
                                    }
                                    
                                    echo '</div>';
                                }

                                // Assuming you have $payment_method defined before this code block
                                $selectedPaymentMethod = isset($_GET['payment']) ? htmlspecialchars($_GET['payment']) : '';

                                echo "<strong><p>Selected Payment Method:</strong> " . $selectedPaymentMethod . "</p>";
                                echo "<strong><p>Selected Voucher code:</strong> " . $selectedVoucherCode . "</p>
                                <br>";
                                echo "<p>" . $roleId . "</p><br>";
                                echo "<tr>
                                    <td colspan='4'></td>
                                    <td><strong>Grand Total:</strong></td>
                                    <td><strong>RM" . number_format($grand_total, 2) . "</strong></td>
                                </tr>
                                <tr>
                                    <td colspan='4'></td>
                                    <td><strong>Voucher Discount:</strong></td>
                                    <td><strong>RM" . number_format($voucher_discount, 2) . "</strong></td>
                                </tr>
                                <tr>
                                    <td colspan='4'></td>
                                    <td><strong>Membership Discount:</strong></td>
                                    <td><strong>RM" . number_format($membership_discount, 2) . "</strong></td>
                                </tr>
                                <tr>
                                    <td colspan='4'></td>
                                    <td><strong>Total after Discount:</strong></td>
                                    <td><strong>RM" . number_format($total_after_discount, 2) . "</strong></td>
                                </tr>";

                                $stmt->close();
                            } else {
                                echo "Error preparing statement: " . $conn->error;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No items in the cart</td></tr>";
                        }

                        // Insert order details into the "orders" table

// Add voucher_code to the SQL query
$sqlInsertOrder = "INSERT INTO orders (user_id, payment_method, total_products, total_payment, vocher_code, status) VALUES (?, ?, ?, ?, ?, ?)";

$stmtInsertOrder = $conn->prepare($sqlInsertOrder);

// Assuming $total_after_discount, $userId, and $selectedPaymentMethod are defined before this point
$status = "Pending";
$total_products = implode(', ',$product_names);
$select_vc = $selectedVoucherCode;

// Update bind_param to include the vocher_id
$stmtInsertOrder->bind_param('issdss', $userId, $selectedPaymentMethod, $total_products, $total_after_discount, $select_vc, $status);


// Check if the preparation was successful
if ($stmtInsertOrder) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Attempt to execute the statement
        if ($stmtInsertOrder->execute()) {
            // Order successfully inserted
            $orderId = $stmtInsertOrder->insert_id;

            // Commit the transaction
            $conn->commit();

            unset($_SESSION['cart']); // or session_destroy();

        } else {
            // Error inserting order
            // Log the error (optional)
            error_log("Error inserting order: " . $stmtInsertOrder->error);
            throw new Exception("Error inserting order.");
        }
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();

        // Display a user-friendly error message or redirect to an error page
        header("Location: checkout.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Error preparing statement
    // Log the error (optional)
    error_log("Error preparing statement: " . $conn->error);

    // Display a user-friendly error message or redirect to an error page
    header("Location: checkout.php?error=Something went wrong. Please try again later.");
    exit();
}

// Close the prepared statement
$stmtInsertOrder->close();

echo "<p>".$select_vc."</p>";
echo "<p>".$total_products."</p>";

                        ?>
                </tbody>
            </table>



            <div class='button-container'>
                <button type="button" class="btn btn-danger" onclick="cancelPayment()">Cancel</button>
                <button type="submit" class="btn btn-primary" onclick="pay()">Pay</button>
            </div>
            </form>
            </div>

            <script>
            function pay() {
                // Display confirmation dialog
                var confirmed = confirm("Are you sure you want to proceed with the payment?");
                
                if (confirmed) {
                    // User clicked OK, submit the form
                    document.getElementById("checkoutForm").submit();
                } else {
                    // User clicked Cancel, do nothing or provide feedback
                    // You can customize this part based on your requirements
                }
            }

            function cancelPayment() {
                // Redirect to cart.php
                window.location.href = "cart.php";
            }
            </script>

    <?php include 'footer.php'; ?>
</body>

</html>