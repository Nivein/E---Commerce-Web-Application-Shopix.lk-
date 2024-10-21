<?php
//took the data from the single page and fetch them with the post request.

session_start();

if(isset($_POST['add_to_cart'])){

  if(isset($_SESSION['cart'])){
  //if user has already added a product to cart.
  $products_array_ids = array_column($_SESSION['cart'],"product_id"); // added id's are returned

  //the below if statement checks whether the product is added to cart or not.
  //checks whether product id is in the $product_array_ids. 
  if(!in_array($_POST['product_id'], $products_array_ids)){
    //if no add this to cart.
    $product_id = $_POST['product_id'];
    $product_array = array(
          'product_id' => $_POST['product_id'], //this is the shotest way of coding for the array
          'product_name' => $_POST['product_name'],
          'product_price' => $_POST['product_price'],
          'product_image' => $_POST['product_image'],
          'product_quantity' => $_POST['product_quantity']
    );
    $_SESSION['cart'][$product_id] = $product_array;
    
  }
  else{
    //if the id exists have to let know the user the id has been addad
    echo '<script>alert("product is already exists in the cart.");</script>';
    
  }
    
  }else{
    //if these is the first product.
    //add them to induvidual parameters and then link them using an array and add this array to a session.
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $product_array = array(
          'product_id' => $product_id,
          'product_name' => $product_name,
          'product_price' => $product_price,
          'product_image' => $product_image,
          'product_quantity' => $product_quantity
    );
    $_SESSION['cart'][$product_id] = $product_array;
  
  }
  calculateTotalCart();
}
//remove product from cart
else if(isset($_POST['remove_product'])){

$product_id = $_POST['product_id'];
unset($_SESSION['cart'][$product_id]);
calculateTotalCart();
}
else if(isset($_POST['edit_quantity'])){
//we get id and quantity from the form
  $product_id = $_POST['product_id'];
  $product_quantity = $_POST['product_quantity'];
//get the product array from the session
  $product_array = $_SESSION['cart'][$product_id];
//update product quantity
  $product_array['product_quantity'] = $product_quantity;
//return array back its place
  $_SESSION['cart'][$product_id] = $product_array;

  //calculate total
  calculateTotalCart();
}
else{
  //header('location: index.php');
}


function calculateTotalCart(){
  $total = 0;

  foreach($_SESSION['cart'] as $key => $value){
    $product = $_SESSION['cart'][$key];

    $price = $product['product_price'];
    $quantity = $product['product_quantity'];

    $total = $total + ($price * $quantity);
  }
  $_SESSION['total'] = $total;
}

?>


<?php include("layouts/header.php");?>

  <!--Cart-->
  <section class="cart container my-5 py-5">
    <div class="container mt-5">
      <h2 class="font-weight-bolde">Your Cart</h2>
    </div>
    <table class="mt-5 pt-5">
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Subtotal</th>
      </tr>

      <?php foreach($_SESSION['cart'] as $key => $value){?>
      <tr>
        <td>
          <div class="product-info">
            <img src="assets/new_images/<?php echo $value['product_image']; ?>">
            <div>
              <p><?php echo $value['product_name']; ?></p>
              <small><span>$</span><?php echo $value['product_price']; ?></small>
              <br>
              <form method="POST" action="cart.php">
                  <input type="hidden" name="product_id" value="<?php echo $value['product_id'];?>"/>
                  <input type="submit" name="remove_product" class="remove-btn" value="remove"/>
              </form>
            </div>
          </div>
        </td>
        <td>
         
          <form method="POST" action="cart.php">
          <input type="hidden" name="product_id" value="<?php echo $value['product_id'];?>"/>
          <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>" />
          <input type="submit" class="edit-btn" value="edit" name="edit_quantity">
          </form>
          
        </td>
        <td>
          <span>$</span>
          <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price'];?></span>
        </td>
      </tr>
      <?php }?>
     
      
    </table>
    <div class="cart-total">
      <table>
        <!--<tr>
          <td>Subtotal</td>
          <td>$ $155 ?></td>
        </tr>-->
        <tr>
          <td>Total Amount</td>
          <td>$ <?php echo $_SESSION['total']; ?></td>
        </tr>
      </table>

    </div>
    <div class="checkout-container">
      <form method="POST" action="checkout.php">
          <input type="submit" class="btn checkout-btn " value="Checkout" name="checkout">
      </form>
    </div>
  </section>


  <?php include("layouts/footer.php"); ?>