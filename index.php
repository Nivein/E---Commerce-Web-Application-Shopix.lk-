<?php
include("layouts/header.php");

?>



  <!--Home-->
  <section id="home">
    <div class="container">
      <h5>NEW ARRIVALS</h5>
      <h1><span>Best Prices</span>This Season</h1>
      <p>Eshop offers the best products for the most affordable prices</p>
      <button>Shop Now</button>
    </div>
  </section>

  <!--Brand-->
  <section id="brand" class="container">
    <div class="row">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/new_images/supreme.jpg">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/new_images/hollister2.jpg">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/new_images/prada.jpg">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/new_images/lacoste.jpeg">
    </div>
  </section>
  <!--NEW-->
  <section id="new" class="w-100">
    <div class="row p-0 m-0">
      <!--One-->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/new_images/shoes_collection.jpg">
        <div class="details">
          <h2>Extremely Awsome Shoes</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>

      <!--Two-->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/new_images/coats.jpeg">
        <div class="details">
          <h2>50% OFF On Coats</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>
      <!--Three-->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/new_images/jacket_collection.jpg">
        <div class="details">
          <h2>Awsome Jackets</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>

  </section>
  <!--featured-->
  <section id="featured" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
      <h3>Our featured</h3>
      <hr class="mx-auto">
      <p>Here you can check out our latest products</p>
    </div>
    <div class="row mx-auto container-fluid">

    <?php include('server/get_featured_product.php');?>

    <?php while($row = $featured_products->fetch_assoc()){?>

      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img src="assets/new_images/<?php echo $row['product_image'];?>" class="img-fluid mb-3">
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name'];?></h5>
        <h4 class="p-price"><?php echo $row['product_price'];?></h4>
        <a href="<?php echo "single_product.php?product_id=". $row['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
      </div>

     <?php } ?> 

    </div>
  </section>

  <!--Banner-->
  <section id="banner" class="my-5 py-5">
    <div class="container">
      <h4>MID SEASON'S SALES</h4>
      <h1>Autumn Collection <br> UP to 30% OFF</h1>
      <button class="text-uppercase">shop now</button>
    </div>
  </section>


  <!--Clothes-->
  <section id="clothes" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Dresses & Coats</h3>
      <hr class="mx-auto">
      <p>Here you can check out our amazing Clothes</p>
    </div>
    <div class="row mx-auto container-fluid">
      <?php include('server/get_coats.php'); ?>
      <?php while($row = $coats_products->fetch_assoc()){ ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img src="assets/new_images/<?php echo $row['product_image']; ?>" class="img-fluid mb-3">
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"> <?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$ <?php echo $row['product_price'];?></h4>
        <button class="buy-btn">Buy Now</button>
      </div>  
      <?php } ?>
    </div>
  </section>
  <!--Watches-->
  <section id="watches" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Watches</h3>
      <hr class="mx-auto">
      <p>check out our unique Watches</p>
    </div>
    <div class="row mx-auto container-fluid">
    <?php include('server/get_watches.php'); ?>
    <?php while($row = $get_watches->fetch_assoc()){ ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img src="assets/new_images/<?php echo $row['product_image']; ?>" class="img-fluid mb-3">
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$ <?php echo $row['product_price'];?></h4>
        <button class="buy-btn">Buy Now</button>
      </div>
      <?php } ?>
    </div>
  </section>
  <!--Shoes-->
  <section id="shoes" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Shoes</h3>
      <hr class="mx-auto">
      <p>Here you can check out our amazing Shoes</p>
    </div>
    <div class="row mx-auto container-fluid">
    <?php include('server/get_Shoes.php'); ?>
    <?php while($row = $get_shoes->fetch_assoc()){ ?>
      <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <img src="assets/new_images/<?php echo $row['product_image']; ?>" class="img-fluid mb-3">
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
        <h4 class="p-price">$ <?php echo $row['product_price']; ?></h4>
        <button class="buy-btn">Buy Now</button>
      </div>
      <?php } ?>
    </div>
  </section>

<?php
include("layouts/footer.php")
?>