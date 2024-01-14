<?php include 'header3.php'; 
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle it as appropriate
    header("Location: login.php");
    exit;
 }?>
<?php include 'about.php'; ?>
<?php include 'footer.php'; ?>