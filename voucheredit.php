<?php
include 'headeradmin.php';
session_start();
require_once 'includes/dbconn.php';

// Assuming you have the voucher ID from the URL
$voucherId = $_GET['id'];

// Fetch voucher details from the database
$sql = "SELECT * FROM voucher WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $voucherId);
$stmt->execute();
$result = $stmt->get_result();

// Check if the voucher exists
if ($result->num_rows > 0) {
    $voucher = $result->fetch_assoc();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update the voucher details in the database
        $voucherCode = $_POST['voucherCode'];
        $description = $_POST['description'];
        $voucherAmount = $_POST['voucherAmount'];

        $sql = "UPDATE voucher SET voucher_code = ?, description = ?, voucherAmount = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $voucherCode, $description, $voucherAmount, $voucherId);

        if ($stmt->execute()) {
            // Voucher updated successfully
            header("Location: voucher.php");
            exit;
        } else {
            // Error in voucher update
            $em = "Error updating voucher.";
        }

        $stmt->close();
    }
} else {
    // Voucher not found, handle accordingly
    echo "Voucher not found!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Voucher</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Voucher</h1>
        <div class="form-container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $voucherId; ?>">
                <div class="form-group">
                    <label for="voucherCode">Voucher Code: </label>
                    <input type="text" class="form-control" id="voucherCode" name="voucherCode" value="<?php echo $voucher['voucher_code']; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description: </label>
                    <input type="text" class="form-control" id="description" name="description" value="<?php echo $voucher['description']; ?>">
                </div>
                <div class="form-group">
                    <label for="voucherAmount">Voucher Amount: </label>
                    <input type="text" class="form-control" id="voucherAmount" name="voucherAmount" value="<?php echo $voucher['voucherAmount']; ?>">
                </div>
                <input class="btn btn-primary" name="submit" type="submit" value="Update Voucher">
                <button class="btn btn-danger" onclick="location.href='voucher.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
