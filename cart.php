<?php
session_start(); // Move session_start to the top
include 'includes/dbconn.php';
// Check if the user is logged in
if (!isset($_SESSION['id'])) {
   // Redirect to the login page or handle it as appropriate
   header("Location: login.php");
   exit;
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

function getDatabaseCart($userId) {
    include 'includes/dbconn.php';

    $databaseCart = array();

    $sql = "SELECT menu_id, quantity FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $menuId = $row['menu_id'];
        $quantity = $row['quantity'];
        $databaseCart[$menuId] = array('quantity' => $quantity);
    }

    $conn->close();

    return $databaseCart;
}


if (isset($_POST['update_btn'])) {
    // Ensure $userId is set
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Extract action and item ID from the button value
        list($action, $itemId) = explode('_', $_POST['update_btn']);

        // Ensure $itemId exists in the user's cart
        if (isset($_SESSION['cart'][$userId][$itemId])) {
            $currentQuantity = $_SESSION['cart'][$userId][$itemId]['quantity'];

            // Perform the database update
            $update_quantity_query = mysqli_prepare($conn, "UPDATE `cart` SET quantity = ? WHERE menu_id = ?");

            if ($update_quantity_query) {
                if ($action === 'increment') {
                    $newQuantity = $currentQuantity + 1;
                } elseif ($action === 'decrement' && $currentQuantity > 1) {
                    $newQuantity = $currentQuantity - 1;
                } else {
                    // No change if decrementing with quantity already at 1
                    $newQuantity = $currentQuantity;
                }

                mysqli_stmt_bind_param($update_quantity_query, 'ii', $newQuantity, $itemId);
                $execution_result = mysqli_stmt_execute($update_quantity_query);

                // Check if the database update was successful
                if ($execution_result) {
                    // Update the quantity for the specific item in the session
                    $_SESSION['cart'][$userId][$itemId]['quantity'] = $newQuantity;
                } else {
                    // Print or log any error messages
                    echo "Error updating quantity: " . mysqli_error($conn);
                }

                mysqli_stmt_close($update_quantity_query);
            } else {
                // Print or log any error messages
                echo "Error preparing update statement: " . mysqli_error($conn);
            }
        }
    }

    header('location:cart.php');
    exit;
}

// Handling the addition of new items to the cart
if (isset($_POST['add_to_cart_btn'])) {
    // Retrieve the new item details from the form
    $newItemId = $_POST['new_item_id'];
    $newItemQuantity = $_POST['new_item_quantity'];

    // Ensure $userId is set
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Check if the new item is already in the cart
        if (isset($_SESSION['cart'][$userId][$newItemId])) {
            // If it's already in the cart, update the quantity
            $_SESSION['cart'][$userId][$newItemId]['quantity'] += $newItemQuantity;

            // Update the database with the new quantity
            $update_quantity_query = mysqli_prepare($conn, "UPDATE `cart` SET quantity = quantity + ? WHERE menu_id = ?");

            if ($update_quantity_query) {
                mysqli_stmt_bind_param($update_quantity_query, 'ii', $newItemQuantity, $newItemId);
                $execution_result = mysqli_stmt_execute($update_quantity_query);

                // Check if the database update was successful
                if (!$execution_result) {
                    // Print or log any error messages
                    echo "Error updating quantity: " . mysqli_error($conn);
                }

                mysqli_stmt_close($update_quantity_query);
            } else {
                // Print or log any error messages
                echo "Error preparing update statement: " . mysqli_error($conn);
            }
        } else {
            // If it's not in the cart, add it
            // Generate a unique cart_id for each menu_id
            $cart_id = uniqid();
            $_SESSION['cart'][$userId][$newItemId] = array(
                'cart_id' => $cart_id,
                'menu_id' => $newItemId,
                'quantity' => $newItemQuantity
            );

            // Insert the new item into the cart table
            $insert_query = mysqli_prepare($conn, "INSERT INTO cart (menu_id, quantity, cart_id) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_query, 'iis', $newItemId, $newItemQuantity, $cart_id);
            mysqli_stmt_execute($insert_query);

            // Check if the insert was successful
            if (!$insert_query) {
                echo "Error inserting into cart: " . mysqli_error($conn);
            }

            mysqli_stmt_close($insert_query);
        }

        // Optionally, you can perform database operations here to add the new item to the database

        header('location:cart.php');
        exit;
    }
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE cart_id = '$remove_id'");
    // Update the session cart information
    session_start();
    $userId = $_SESSION['id'];
    unset($_SESSION['cart'][$userId][$remove_id]);
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart`");
    // Update the session cart information
    session_start();
    $userId = $_SESSION['id'];
    unset($_SESSION['cart'][$userId]);
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
    }

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
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price (RM)</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'header3.php';
                    echo "<h2>Shopping Cart</h2>";

                    // Check if the user is logged in
                    if (!isset($_SESSION['id'])) {
                        header('Location: login.php');
                        exit;
                    }

                    require_once 'includes/dbconn.php';
                    $userId = $_SESSION['id'];

                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                        $cartItems = isset($_SESSION['cart'][$userId]) ? $_SESSION['cart'][$userId] : array();

                        if (!empty($cartItems)) {
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

                                while ($row = $result->fetch_assoc()) {
                                    $itemId = $row['id'];
                                    
                                    // Ensure that $update_value for new items is correctly captured
                                    $update_value = isset($_POST['update_quantity'][$itemId]) ? $_POST['update_quantity'][$itemId] : $_SESSION['cart'][$userId][$itemId]['quantity'];
                                    
                                    $sub_total = floatval($update_value) * floatval($row['price']);
                                    $grand_total += $sub_total;
                                    
                                    echo "<tr>";
                                    echo "<td>" . $counter . "</td>";
                                    echo "<td><img alt='Image' width='200' src='images/{$row['image']}'></td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>RM" . $row['price'] . "</td>";
                                    echo "<td>
                                            <form action='cart.php?update={$itemId}' method='post'>
                                                <input type='hidden' name='update_quantity_id' value='{$itemId}'>
                                                <div class='quantity-input-container'>
                                                    <button type='submit' name='update_btn' value='decrement_{$itemId}' class='btn btn-primary quantity-btn'>-</button>
                                                    <input type='number' name='update_quantity[{$itemId}]' min='1' value='{$update_value}' class='quantity-input'>
                                                    <button type='submit' name='update_btn' value='increment_{$itemId}' class='btn btn-primary quantity-btn'>+</button>
                                                </div>
                                            </form>
                                        </td>";
                                    echo "<td><a href='cart.php?remove={$itemId}' onclick='return confirm(\"Remove item from cart?\")' class='btn btn-danger delete-btn'> <i class='fas fa-trash'></i> Remove</a></td>";
                                    echo "</tr>";
                                    
                                    $counter++;
                                }                                
                                
                                echo "<tr>
                                        <td colspan='3'></td>
                                        <td><strong>Grand Total:</strong></td>
                                        <td><strong>RM" . number_format($grand_total, 2) . "</strong></td>
                                        <td></td>
                                      </tr>";

                                $stmt->close();
                            } else {
                                // Handle the prepare error
                                echo "Error preparing statement: " . $conn->error;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No items in the cart</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No items in the cart</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>

            <h3> Your Information </h3>

            <?php
                $roleId = $user['role_id'];

                include 'includes/dbconn.php';

                // Initialize variables to store the selected delivery method and voucher code
                $selectedDelivery = isset($_GET['delivery']) ? $_GET['delivery'] : 'delivery';
                $selectedVoucher = isset($_GET['voucherDropdown']) ? $_GET['voucherDropdown'] : '';

                echo "<form action='checkout.php' method='get'>";
                echo "<strong><p> Delivery Method:</strong></p>";
                echo "<div class='form-check'>";
                echo "<input class='form-check-input' type='radio' name='delivery' id='delivery' value='delivery' " . ($selectedDelivery === 'delivery' ? 'checked' : '') . ">";
                echo "<label class='form-check-label' for='delivery'>Delivery</label>";
                echo "</div>";
                echo "<div class='form-check'>";
                echo "<input class='form-check-input' type='radio' name='delivery' id='pickup' value='pickup' " . ($selectedDelivery === 'pickup' ? 'checked' : '') . ">";
                echo "<label class='form-check-label' for='pickup'>Pickup</label>";
                echo "</div>";
                echo "<br>";
                    
                    // Assuming $userId and $roleId are defined somewhere before this code block
                    
                    if ($roleId == 3) {
                        // Display claimed vouchers for role_id == 3
                    
                        // Use prepared statement to prevent SQL injection
                        $claimedVoucherQuery = $conn->prepare("SELECT voucher_code FROM claimed_voucher WHERE user_id = ?");
                        $claimedVoucherQuery->bind_param("i", $userId);
                        $claimedVoucherQuery->execute();
                        $claimedVoucherResult = $claimedVoucherQuery->get_result();
                    
                        if ($claimedVoucherResult) {
                            $claimedVouchers = $claimedVoucherResult->fetch_all(MYSQLI_ASSOC);
                    
                            // Free the result set
                            $claimedVoucherQuery->close();
                    
                            if ($claimedVouchers) {
                                echo "<strong><label for='voucherDropdown'>Select a voucher:</label></strong>";
                                echo "<select class='form-control' id='voucherDropdown' name='voucherDropdown'>";
                    
                                // Debug: Check claimed vouchers
                                var_dump($claimedVouchers);
                    
                                foreach ($claimedVouchers as $voucher) {
                                    $voucherCode = $voucher['voucher_code'];
                                    $voucherUsed = $voucher['used'];
                    
                                    // Debug: Check each voucher code and its usage status
                                    var_dump($voucherCode, $voucherUsed);
                    
                                    if (!$voucherUsed) {
                                        echo "<option value='$voucherCode' " . ($selectedVoucher === $voucherCode ? 'selected' : '') . ">$voucherCode</option>";
                                    } else {
                                        // Debug: Print a message if the voucher has been used
                                        echo "<p>Voucher $voucherCode has already been used.</p>";
                                    }
                                }
                    
                                echo "</select>";
                            } else {
                                echo "<p>No claimed vouchers available.</p>";
                            }
                        } else {
                            // Handle the query error
                            echo "Error fetching claimed vouchers: " . $conn->error;
                        }
                    
                        echo "<br>";
                    }                    

                echo "<div class='form-group'>";
                echo "<p><label for='payment'>Payment Method:</label></p>";
                echo "<select class='form-control' id='payment' name='payment' required>";
                echo "<option value='Credit Card'>Credit Card</option>";
                echo "<option value='PayPal'>PayPal</option>";
                echo "<option value='Touch N Go'>Touch 'n Go</option>";
                echo "</select>";
                echo "</div>";
                echo "<br>";
                echo "<div class='button-container'>";
                echo "<a href='menucustomer.php' class='btn btn-primary'>Continue Shopping</a>";
                echo "<button type='submit' class='btn btn-primary'>Checkout</button>";
                echo "</div>";
                echo "</form>";
            ?>

        </div>
    </div>
</body>

</html>

<?php include 'footer.php'; ?>
