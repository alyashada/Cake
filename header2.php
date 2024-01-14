<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title> Heavenly Cravings Cake </title>
        
        <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <header>
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
                            <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutlanding.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="menu.php">Menu</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mt-2 mt-lg-0">
                        <li class="nav-item">
                            <?php
                                echo "<form action='login.php' method='post'>";
                                echo "<button type='submit' class='btn btn-secondary mr-2'>Login</button>";
                                echo "</form>";
                            ?>
                        </li>
                        <li class="nav-item">
                            <?php
                                echo "<form action='signup.php' method='post'>";
                                echo "<button type='submit' class='btn btn-secondary'>Signup</button>";
                                echo "</form>";
                            ?>
                        </li>
                    </ul>
                </div>
            </nav>
    </header>

</html>