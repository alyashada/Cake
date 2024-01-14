<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title> Heavenly Cravings Cake </title>
        
        <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <header class="hcs-header landing-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light hcs-navbar">
                <a class="navbar-brand">
                    <img src="images/logo.png" alt="HCS" width="100" height="100"> <b> Heavenly Cravings Cake </b>
                </a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="indexdelight.php">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutcustomer.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="menucustomer.php">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ordercust.php">Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mt-2 mt-lg-0">
                        <li class="nav-item">
                            <?php
                                echo "<form action='logout.php' method='post'>";
                                echo "<button type='submit' class='btn btn-secondary'>Logout</button>";
                                echo "</form>";
                            ?>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container">
                <div class="header-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h1>Heavenly Cravings Cake</h1>
                            <p class="text-dark">Savor the magic. Taste the bliss. Welcome to a world of Heavenly Delights at Heavenly Cravings Cake!</p>
                        </div>
                        <div class="col-md-6">
                            <img src="images/cake_header.png" alt="cake_header" width="75%"class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

</html>