<?php
include 'headeradmin.php';
session_start();
require_once 'includes/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit']) && isset($_FILES['image'])) {

        $img_name = $_FILES['image']['name'];
        $img_size = $_FILES['image']['size'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $error = $_FILES['image']['error'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        if ($error === 0) {
            if ($img_size > 125000) {
                $em = "Sorry, your file is too large.";
                header("Location: productadd.php?error=$em");
                exit;
            }

            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $img_upload_path = 'images/' . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

                // Insert into Database
                $sql = "INSERT INTO menu (image, name, description, price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $new_img_name, $name, $description, $price);

                if ($stmt->execute()) {
                    // Record inserted successfully
                    header("Location: menuadmin.php");
                    exit;
                } else {
                    // Error in record insertion
                    $em = "Error adding product to menu.";
                }
                
                $stmt->close(); // Close the statement after use
            } else {
                // Error if file type not allowed
                $em = "You can't upload files of this type";
            }
        } else {
            // Unknown error occurred
            $em = "Unknown error occurred!";
        }

        // Redirect with error message
        header("Location: productadd.php?error=$em");
        exit;
    } else {
        // Form not submitted correctly
        header("Location: productadd.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Menu</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add Menu</h1>
        <div class="form-container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="formFile" class="form-label">Image: </label>
                    <input class="form-control" type="file" id="formFile" name="image" accept="image/png, image/jpg, image/jpeg">
                </div>
                <div class="form-group">
                    <label for="name">Name: </label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="description">Description: </label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                </div>
                <div class="form-group">
                    <label for="price">Price: </label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="Price">
                </div>
                <input class="btn btn-primary" name="submit" type="submit" value="Add Menu">
                <button class="btn btn-danger" onclick="location.href='menuadmin.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
