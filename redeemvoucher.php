<?php
session_start();
require_once 'includes/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $redeemCode = $_POST["redeemCode"];

    // Check if the voucher code exists
    $stmt = mysqli_prepare($conn, "SELECT id, voucherAmount FROM voucher WHERE voucher_code = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $redeemCode);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Voucher code exists, fetch the data
        mysqli_stmt_bind_result($stmt, $voucherId, $voucherAmount);
        mysqli_stmt_fetch($stmt);

        // Check if there's remaining amount on the voucher
        if ($voucherAmount > 0) {
            // Deduct an amount (e.g., $1) for simplicity, adjust as needed
            $deductionAmount = 1;
            $newVoucherAmount = $voucherAmount - $deductionAmount;

            // Update the voucher amount
            $updateStmt = mysqli_prepare($conn, "UPDATE voucher SET voucherAmount = ? WHERE id = ?");
            mysqli_stmt_bind_param($updateStmt, "di", $newVoucherAmount, $voucherId);
            
            if (mysqli_stmt_execute($updateStmt)) {
                echo "Voucher redeemed successfully!";
            } else {
                echo "Error updating voucher: " . mysqli_error($conn);
            }

            mysqli_stmt_close($updateStmt);
        } else {
            echo "Voucher has no remaining amount.";
        }
    } else {
        echo "Invalid voucher code.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
