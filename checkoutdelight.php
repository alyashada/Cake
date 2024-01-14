<?php

@include 'includes/dbconn.php';

// Function to calculate the discount based on the total price
function calculateDiscount($totalPrice) {
    $discount = 0;

    // Check if the total price is more than $40
    if ($totalPrice > 40) {
        $discount = 10;
    }

    return $discount;
}

$discount = 0;  // Initialize discount variable

if(isset($_POST['order_btn'])){
    $name = $_POST['name'];
    $number = $_POST['number'];
    $method = $_POST['method'];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
    $price_total = 0;

    if(mysqli_num_rows($cart_query) > 0){
        while($product_item = mysqli_fetch_assoc($cart_query)){
            $product_name[] = $product_item['name'] .' ('. $product_item['quantity'] .') ';
            $product_price = $product_item['price'] * $product_item['quantity'];

            $price_total += $product_price;
        };

        // Calculate the discount for the entire order
        $discount = calculateDiscount($price_total);
        // Subtract the discount from the total price for the order
        $price_total -= $discount;
    }

    $total_product = implode(', ',$product_name);
    $detail_query = mysqli_query($conn, "INSERT INTO `order`(name, number, method, total_products, total_price) VALUES('$name','$number','$method','$total_product','$price_total')") or die('query failed');

    if($cart_query && $detail_query){
        echo "
            <div class='order-message-container'>
                <div class='message-container'>
                    <h3>thank you for shopping!</h3>
                    <div class='order-detail'>
                        <span>".$total_product."</span>
                        <span class='total'> total : $".$price_total."/-  </span>
                        
                    </div>
                    <div class='customer-details'>
                        <p> your name : <span>".$name."</span> </p>
                        <p> your number : <span>".$number."</span> </p>
                        <p> your payment mode : <span>".$method."</span> </p>
                        <p>(Payment completed!)</p>
                    </div>
                    <a href='menudelight.php' class='btn'>continue shopping</a>
                </div>
            </div>
        ";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'headerdelight.php'; ?>

<div class="container">

<section class="checkout-form">

   <h1 class="heading">complete your order</h1>

   <form action="" method="post">

   <div class="display-order">
   <?php
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
        $total = 0;
        $grand_total = 0;
        $discount_total = 0;  // Initialize discount total

        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total = $total += $total_price;
            }

            // Calculate the discount for the entire order
            $discount = calculateDiscount($grand_total);

            // Calculate the discounted total
            $discount_total = $grand_total - $discount;
        } else {
            echo "<div class='display-order'><span>your cart is empty!</span></div>";
        }
        ?>

        <div class="display-order">
            <?php
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");

            if(mysqli_num_rows($select_cart) > 0){
                while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                    ?>
                    <span><?= $fetch_cart['name']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
                    <?php
                }
            }
            ?>
            <span class="grand-total"> grand total : $<?= $grand_total; ?>/- </span>
            <span class="discount"> discount : $<?= $discount; ?>/- </span>
            <span class="total"> total after discount : $<?= $discount_total; ?>/- </span>
        </div>


      <div class="flex">
         <div class="inputBox">
            <span>your name</span>
            <input type="text" placeholder="enter your name" name="name" required>
         </div>
         <div class="inputBox">
            <span>your number</span>
            <input type="number" placeholder="enter your number" name="number" required>
         </div>
         <div class="inputBox">
            <span>payment method</span>
            <select name="method">
               <option value="credit cart">credit cart</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
      <input type="submit" value="Pay" name="order_btn" class="btn">
   </form>

</section>

</div>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
