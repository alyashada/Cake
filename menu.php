<?php include 'header2.php'; ?>
<?php session_start(); ?>
<?php require_once 'includes/dbconn.php'; ?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title> Menu </title>
        
        <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="style.css">
    </head>

<body>
    <div class="container">
        <h2 class="section-title">Heavenly Cakes - Menu</h2>
        <p class="text-dark">Elevate your senses with our heavenly slices of cake, each a masterpiece of moistness and flavor. From classic favorites to innovative creations, our cakes are crafted to make every moment special.</p>
        <br>

        <div class="search-form d-flex justify-content-center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="form-inline my-2 my-lg-0">
                <input type="text" class="form-control mr-sm-2" name="search" placeholder="Search Name">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <br>
        </div>


        <?php
            // Assuming you've already established the database connection ($conn)

            // Set the search term
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

            // Prepare the query with the search term using prepared statements
            $query = "SELECT * FROM menu WHERE id LIKE ? OR name LIKE ?";
            $stmt = mysqli_prepare($conn, $query);

            if (!$stmt) {
                die("Error in preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters
            $searchParam = "%$searchTerm%";
            mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);

            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                die("Error in executing statement: " . mysqli_error($conn));
            }

            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Initialize a counter for each product in a row
            $productCounter = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                // Start a new row for every 3 products
                if ($productCounter % 3 == 0) {
                    echo "<div class='row'>";
                }

                echo "<div class='col-lg-4 mb-4 mb-lg-0'>";
                echo "<div class='product__item'>";

                echo "<div class='product__item__pic set-bg' data-setbg='data:image/jpg;base64," . base64_encode($row['image']) . "'>";
                echo "<center><br><img src='data:image/jpg;base64," . base64_encode($row['image']) . "' alt='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "' width='270' class='img-fluid'></center>";
                echo "<div class='product__label'>";
                echo "<span>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div class='product__item__text'>";
                echo "<p>" . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<div class='product__item__price'>RM" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</div>";
                echo "<div class='cart_add'>";
                echo "<a href='login.php?id=" . $row['id'] . "' class='btn btn-primary'>Add to Cart</a>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
                echo "</div>";

                // Close the row after every 3 products
                if ($productCounter % 3 == 2) {
                    echo "</div>";
                }

                // Increment the product counter
                $productCounter++;
            }

            // If the number of products is not a multiple of 3, close the last row
            if ($productCounter % 3 != 0) {
                echo "</div>";
            }

            // Close the result set
            mysqli_free_result($result);

            // Close the statement
            mysqli_stmt_close($stmt);

            // Close the connection
            mysqli_close($conn);
        ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
