<?php
include 'headeradmin.php';
session_start();
require_once 'includes/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/' . $image;
        $image_size = $_FILES['image']['size'];

        // Check file size (max 64KiB)
        if ($image_size > 65536) {
            echo "Error: File size exceeds the maximum allowed (64KiB).";
        } else {
            // Move uploaded file to the specified folder
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO `menu` (image, name, description, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $image, $name, $description, $price);

                // Execute the prepared statement
                if ($stmt->execute()) {
                    echo "Product successfully added.";
                } else {
                    echo "Error adding product: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error uploading file.";
            }
        }
    } else {
        echo "No file uploaded or an error occurred during upload.";
    }
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="assets/vendors/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>
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
                <input class="btn btn-primary" type="submit" value="Add Product">
                <button class="btn btn-danger" onclick="location.href='menuadmin.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
