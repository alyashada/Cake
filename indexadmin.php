<?php

include 'headeradmin.php';
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle it as appropriate
    header("Location: login.php");
    exit;
}
?>
<p> Index Admin </p>

<?php
include 'footer.php';
?>
