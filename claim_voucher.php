<?php
session_start(); // Add this line at the beginning
include 'includes/dbconn.php';

// Check if it's a POST request and contains the voucher code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voucherCode'])) {
    $voucherCode = $_POST['voucherCode'];

    // Assuming you have a user ID (you need to modify this based on your authentication mechanism)
    $userId = $_SESSION['id']; // Replace with your user ID

    // Retrieve the voucher from the database
    $sqlSelectVoucher = "SELECT * FROM voucher WHERE voucher_code = ?";
    $stmtSelectVoucher = $conn->prepare($sqlSelectVoucher);
    $stmtSelectVoucher->bind_param('s', $voucherCode);
    $stmtSelectVoucher->execute();
    $resultSelectVoucher = $stmtSelectVoucher->get_result();

    if ($resultSelectVoucher->num_rows > 0) {
        $voucher = $resultSelectVoucher->fetch_assoc();

        // Check if the voucher has not been claimed
        if (empty($voucher['claimed_by'])) {
            // Update the voucher status to claimed
            $sqlUpdateVoucher = "UPDATE voucher SET claimed_by = ? WHERE voucher_code = ?";
            $stmtUpdateVoucher = $conn->prepare($sqlUpdateVoucher);
            $stmtUpdateVoucher->bind_param('ss', $userId, $voucherCode);

            if ($stmtUpdateVoucher->execute()) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update voucher status.', 'error' => $stmtUpdateVoucher->error]);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Voucher already claimed.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Voucher not found.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
?>
