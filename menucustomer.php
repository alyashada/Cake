<?php
session_start();
include 'header3.php';
@include 'includes/dbconn.php';

$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (isset($_GET['id'])) {
    $menuId = $_GET['id'];

    if (isset($_SESSION['cart'][$userId][$menuId])) {
        $_SESSION['cart'][$userId][$menuId]['quantity']++;
    } else {
        $sql = "SELECT * FROM menu WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $menuId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['cart'][$userId][$menuId] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => 1
            );
        }
    }

    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
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
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $query = "SELECT * FROM menu WHERE id LIKE ? OR name LIKE ?";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            die("Error in preparing statement: " . mysqli_error($conn));
        }

        $searchParam = "%$searchTerm%";
        mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);

        if (!mysqli_stmt_execute($stmt)) {
            die("Error in executing statement: " . mysqli_error($conn));
        }

        $result = mysqli_stmt_get_result($stmt);
        $productCounter = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($productCounter % 3 == 0) {
                echo "<div class='row'>";
            }

            echo "<div class='col-lg-4 mb-4 mb-lg-0'>";
            echo "<div class='product__item'>";

            echo "<div class='product__item__pic'>";
            echo "<center><br><img alt='Image' src='images/{$row['image']}' width='270' class='img-fluid'></center>";
            echo "<div class='product__label'>";
            echo "<span>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</span>";
            echo "</div>";
            echo "</div>";

            echo "<div class='product__item__text'>";
            echo "<p>" . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<div class='product__item__price'>RM" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</div>";
            echo "<div class='cart_add'>";
            echo "<a href='menucustomer.php?id=" . $row['id'] . "' class='btn btn-primary' name='add_to_cart_btn'>Add to Cart</a>";
            echo "</div>";
            echo "</div>";

            echo "</div>";
            echo "</div>";

            if ($productCounter % 3 == 2) {
                echo "</div>";
            }

            $productCounter++;
        }

        if ($productCounter % 3 != 0) {
            echo "</div>";
        }

        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
