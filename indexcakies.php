<?php
include 'headercustomer.php';
?>

<?php
session_start();
include 'includes/dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle it as appropriate
    header("Location: login.php");
    exit;
}
// Add any necessary logic for updating the cart or processing the checkout
$deliveryMethod = isset($_POST['delivery']) ? $_POST['delivery'] : '';

// Fetch user details from the database
$userId = $_SESSION['id'];
$sqlUser = "SELECT * FROM user WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);

if (!$stmtUser->execute()) {
    echo 'Error in user query: ' . $stmtUser->error;
    exit;
}

$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $user = $resultUser->fetch_assoc();
} else {
    // User not found, handle accordingly
    echo "User not found!";
    exit;
}

// Handle voucher claiming
if (isset($_POST['claim_voucher']) && isset($_POST['voucher'])) {
    $voucherCode = $_POST['voucher'];

    // Check if the user has already claimed a voucher
    $sqlCheckClaimed = "SELECT * FROM claimed_voucher WHERE user_id = ? AND voucher_code = ?";
    $stmtCheckClaimed = $conn->prepare($sqlCheckClaimed);
    $stmtCheckClaimed->bind_param('is', $userId, $voucherCode);

    if (!$stmtCheckClaimed->execute()) {
        echo '<script>alert("Error checking claimed voucher.");</script>';
        exit;
    }

    $resultCheckClaimed = $stmtCheckClaimed->get_result();

    if ($resultCheckClaimed->num_rows > 0) {
        echo '<script>alert("You have already claimed this voucher.");</script>';
        exit;
    }

    // Update the claimed voucher in the session
    $_SESSION['claimed_voucher'] = $voucherCode;

    // Insert a record in claimed_vouchers table to track the claimed voucher by the user
    $sqlInsertClaimedVoucher = "INSERT INTO claimed_voucher (user_id, voucher_code) VALUES (?, ?)";
    $stmtInsertClaimedVoucher = $conn->prepare($sqlInsertClaimedVoucher);
    $stmtInsertClaimedVoucher->bind_param('is', $userId, $voucherCode);

    if (!$stmtInsertClaimedVoucher->execute()) {
        echo '<script>alert("Failed to claim voucher.");</script>';
        exit;
    }

    // Update the voucher status in the database (deduct voucherAmount by 1)
    $sqlUpdateVoucher = "UPDATE voucher SET voucherAmount = voucherAmount - 1 WHERE voucher_code = ?";
    $stmtUpdateVoucher = $conn->prepare($sqlUpdateVoucher);
    $stmtUpdateVoucher->bind_param('s', $voucherCode);

    if (!$stmtUpdateVoucher->execute()) {
        echo '<script>alert("Failed to update voucher status.");</script>';
        exit;
    }

    echo '<script>alert("Voucher claimed successfully.");</script>';
    exit;
}
?>

<html>

<head>
</head>

<body>

<main class="page-about">
    <div class="container">
        <section class="foi-page-section pt-0">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0 pr-lg-0">
                    <h2 class="about-section-one-title">
                        🍰 Welcome to the Heavenly Cravings Cake Family, Cakies! 🎉
                    </h2>
                    <div class="about-section-one-content">
          
                        <p>Dear Cakies,</p>
                   
                        <p>Congratulations on becoming an official member of the Heavenly Cravings Cake family! We are delighted to have you as part of our exclusive Cakies community, where sweet moments and delightful experiences await.</p>

                        <h4>🌟 What It Means to Be a Cakies:</h4>
                
                        <p class="mb-0">As a Cakie, you are not just a member; you're a cherished part of our extended family. Get ready to indulge in a world of heavenly treats, exclusive perks, and mouthwatering surprises. Your love for cakes has found its perfect home!</p>
                    </div>
                </div>
                <div class="col-md-6 pl-lg-0 d-flex align-items-center align-items-lg-end">
                    <img src="images\welcome Cakies.jpeg" alt="about" class="img-fluid" width="448px">
                </div>
            </div>
        </section>
        <section class="foi-page-section">
            <h2 class="team-section-title">🎁 Exclusive Member Benefits:</h2>
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <img src="images\cakes2.jpeg" alt="about 2" class="w-100 img-fluid pr-md-5" width="437px">
                </div>
                <div class="col-md-6">
                    <div class="about-section-two-content">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Indulge in our special menu and enjoy an exclusive RM15 off when you spend RM65 or more! </h5>
                        </div>
                    </div>
                        <br>
                        <p>Embark on a culinary journey with our specially curated special menu. Each dish is a masterpiece, designed to tantalize your taste buds and elevate your dining experience. </p>
                        <h4>Save While You Savor:</h4>
                        <p>As a token of our appreciation, we're delighted to offer you an exclusive RM15 off when you explore our special menu and spend RM65 or more. Picture this – a delightful fusion of flavors, complemented by generous savings. </p>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="container">
    <div class="voucher-section">
        <h3>Available Vouchers</h3>
        <?php
        // Assuming you have a database connection
        include 'includes/dbconn.php';

        // Retrieve all vouchers from the database
        $sqlVouchers = "SELECT * FROM voucher";
        $resultVouchers = $conn->query($sqlVouchers);

        if ($resultVouchers === false) {
            echo 'Error in vouchers query: ' . $conn->error;
            exit;
        }

        if ($resultVouchers->num_rows > 0) {
            while ($voucher = $resultVouchers->fetch_assoc()) {
                echo '<div class="voucher-box">';
                echo '<p>Voucher Code: ' . $voucher['voucher_code'] . '</p>';
                echo '<p>Description: ' . $voucher['description'] . '</p>';

                // Check if the voucher is claimed by the user
                $claimedClass = '';
                $claimStatus = 'Claim';

                // Check if the user has already claimed a voucher
                $sqlCheckClaimed = "SELECT * FROM claimed_voucher WHERE user_id = ? AND voucher_code = ?";
                $stmtCheckClaimed = $conn->prepare($sqlCheckClaimed);
                $stmtCheckClaimed->bind_param('is', $userId, $voucher['voucher_code']);

                if (!$stmtCheckClaimed->execute()) {
                    echo 'Error in check claimed query: ' . $stmtCheckClaimed->error;
                    exit;
                }

                $resultCheckClaimed = $stmtCheckClaimed->get_result();

                if ($resultCheckClaimed->num_rows > 0) {
                    $claimedClass = 'claimed';
                    $claimStatus = 'Claimed';
                }

                echo '<form method="post" action="">';
                echo '<input type="hidden" name="voucher" value="' . $voucher['voucher_code'] . '">';
                echo '<button class="claim-button btn btn-primary ' . $claimedClass . '" type="submit" name="claim_voucher" onclick="claimVoucher(this)" ' . ($claimedClass == 'claimed' ? 'disabled' : '') . '>' . $claimStatus . '</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<p>No vouchers available.</p>';
        }
        ?>
    </div>
</div>
</main>

<script src="assets/vendors/jquery/jquery.min.js"></script>
<script src="assets/vendors/popper.js/popper.min.js"></script>
<script src="assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>



<!-- JavaScript Function for Claiming Voucher -->
<script>
// JavaScript Function for Claiming Voucher
function claimVoucher(button) {
    var voucherCodeInput = button.parentElement.querySelector('[name="voucher"]');
    var voucherCode = voucherCodeInput.value;

    fetch('claim_voucher.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'voucherCode=' + encodeURIComponent(voucherCode),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Optional: Reload the page after claiming
    })
    .catch(error => {
        console.error('Error claiming voucher:', error);
    });
}
    // Check if the user is logged in
    const userId = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 'null'; ?>;
    
    if (!userId) {
        // User is not logged in, redirect to login page
        alert("Please log in to continue.");
        window.location.href = "login.php";
    }

    // Disable the back button
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
</script>


<?php
include 'footer.php';
?>