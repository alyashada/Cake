<?php
include 'headercustomer2.php';
session_start();
include 'includes/dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle it as appropriate
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Heavenly Cake Shop | Delight Member</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
    .about-section-one-content img {
        width: 100%; /* Set the width to 100% for full container width */
        max-width: 100%; /* Ensure the image doesn't exceed its natural size */
        height: auto; /* Maintain the aspect ratio */
        transition: opacity 0.3s ease-in-out;
    }

    .about-section-one-content img:hover {
        opacity: 0.8;
    }
    </style>
</head>

<body>

    <main class="page-about">
        <div class="container">
            <section class="foi-page-section pt-0">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0 pr-lg-0">
                        <h2 class="about-section-one-title">
                        🍰 Welcome to Heavenly Cravings Cake, Delight Member! 🎉
                        </h2>
                        <div class="about-section-one-content">
              
                            <p>Dear Delight,</p>
                       
                            <p>Congratulations on becoming an esteemed member of our exclusive Delight family at Heavenly Cravings Cake! Get ready for a delightful journey filled with sweet surprises, exclusive treats, and indulgent moments that will elevate your cake experience to new heights.</p>

                            <h4>🌟 A Sweet Journey Awaits You:</h4>
                    
                            <p class="mb-0">As a Delight, you are not just a member; you're a cherished part of our extended family. Get ready to indulge in a world of heavenly treats, exclusive perks, and mouthwatering surprises. Your love for cakes has found its perfect home!</p>
                        </div>
                    </div>
                    <div class="col-md-6 pl-lg-0 d-flex align-items-center align-items-lg-end">
                        <img src="images\welocmee deligt.jpeg" alt="about" class="img-fluid" width="448px">
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
                                <h5 class="card-title">💰 Exclusive offer just for you! Get RM10 off with every purchase, and relish the discounts up to RM70. It's our way of sweetening your experience. </h5>
                            </div>
                        </div>
                            <br>
                            <h4>🍰 Savor the Sweetness, Save Along the Way:</h4>
                            <p>As a token of our appreciation for being a cherished Delight Member, we're thrilled to extend an exclusive RM10 off when you indulge in our special menu and spend RM70 or more. Picture this – a symphony of flavors crafted just for you, harmonized with the joy of generous savings. Your delightful journey awaits!</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="foi-page-section pt-0 text-center">
                <h2 class="team-section-title">🍰 Indulge and Save with Our Sweet Coupons! 🎉</h2>
                <div class="row justify-content-center">
                    <!-- Centered Image -->
                    <div class="col-md-6">
                        <div class="about-section-one-content">
                            <img src="images\coupon2.png" class="img-fluid" alt="Special Menu Image 1">
                        </div>
                    </div>
                </div>
            </section>



        </div>
    </main>

    <script src="assets/vendors/jquery/jquery.min.js"></script>
    <script src="assets/vendors/popper.js/popper.min.js"></script>
    <script src="assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
include 'footer.php';
?>