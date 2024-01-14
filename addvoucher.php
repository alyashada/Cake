<?php
include 'headeradmin.php';
session_start();
require_once 'includes/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $voucherCode = $_POST["voucherCode"];
    $description = $_POST["description"];
    $voucherAmount = $_POST["voucherAmount"];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO `voucher` (voucher_code, description, voucherAmount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $voucherCode, $description, $voucherAmount);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Voucher successfully added.";
    } else {
        echo "Error adding voucher: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Voucher</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add Voucher</h1>
        <div class="form-container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="voucherCode">Voucher Code: </label>
                    <input type="text" class="form-control" id="voucherCode" name="voucherCode" placeholder="Voucher Code" required>
                </div>
                <div class="form-group">
                    <label for="description">Description: </label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description" required>
                </div>
                <div class="form-group">
                    <label for="voucherAmount">Voucher Amount: </label>
                    <input type="text" class="form-control" id="voucherAmount" name="voucherAmount" placeholder="Voucher Amount" required>
                </div>
                <input class="btn btn-primary" type="submit" value="Add Voucher">
                <button class="btn btn-danger" onclick="location.href='voucher.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
