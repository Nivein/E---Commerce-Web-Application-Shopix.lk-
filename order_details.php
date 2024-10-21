<?php


// not paid
// delivered
// shipped
// paid
include('server/connection.php');

if(isset($_POST['order_details_btn']) && isset($_POST['order_id'])){

    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");

    $stmt->bind_param('i',$order_id);

    $stmt->execute();

    $order_details = $stmt->get_result();
}else{
    header('location: account.php');
    exit;
}

?>





<?php include("layouts/header.php");?>

     <!--Order details-->
     <section id="orders" class="orders container my-5 py-5">
        <div class="container mt-5">
            <h2 class="font-weight-bolde text-center">Order details</h2>
            <hr class="mx-auto">
        </div>
        <table class="mt-5 pt-5 mx-auto">
            <tr>
                <th>Product name</th>
                <th>Price</th>
                <th>Quantity</th>
                
            </tr>

            <?php while($row= $order_details->fetch_assoc()){ ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="assets/new_images/<?php echo $row['product_image'];?>" alt=""> 
                                <div>
                                    <p class="mt-3"><?php echo $row['product_name'];?></p>
                                </div>
                            </div> 
                        </td>
                        <td>
                            <span>$ <?php echo $row['product_price'];?></span>
                        </td>
                        <td>
                            <span><?php echo $row['product_quantity'];?></span>
                        </td>
                       
                           
                    </tr>
            <?php } ?>

        </table>
    <?php if($order_status == "not paid"){ ?>

        <form style="float: right;">
            <input type="submit" class="btn btn-primary" value="Pay Now"/>
        </form>

    <?php } ?>
    
    </section>

    <!--Footer-->
 <footer class="mt-5 py-5">
        <div class="row container mx-auto pt-5">
            <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <img class="logo" src="assets/new_images/logo.png" alt="">
                <p class="pt-3">We provide the best products for the most affordable prices</p>
            </div>
            <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <h5 class="pb-2">Featured</h5>
                <ul class="text-uppercase">
                    <li><a href="#">Men</a></li>
                    <li><a href="#">Women</a></li>
                    <li><a href="#">Boys</a></li>
                    <li><a href="#">Girls</a></li>
                    <li><a href="#">New Arrivals</a></li>
                    <li><a href="#">Clothes</a></li>
                </ul>
            </div>
            <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <h5 class="pb-2">Contact Us</h5>
                <div>
                    <h6 class="text-uppercase">Address</h6>
                    <p>1234 street name, city</p>
                </div>
                <div>
                    <h6 class="text-uppercase">Phone</h6>
                    <p>123 456 789</p>
                </div>
                <div>
                    <h6 class="text-uppercase">Email</h6>
                    <p>info@email.com</p>
                </div>
            </div>
            <div class="footer-one col-lg-3 col-md-6 col-sm-12">
                <h5 class="pb-2">Dealers</h5>
                <div class="row">
                    <img src="assets/new_images/lacoste.jpeg" alt="" class="img-fluid w-25 h-100 m-2">
                    <img src="assets/new_images/hugo_boss.jpeg" alt="" class="img-fluid w-25 h-100 m-2">
                    <img src="assets/new_images/levis.jpeg" alt="" class="img-fluid w-25 h-100 m-2">
                    <img src="assets/new_images/nike.jpeg" alt="" class="img-fluid w-25 h-100 m-2">
                    <img src="assets/new_images/channel.jpeg" alt="" class="img-fluid w-25 h-100 m-2">
                </div>
            </div>
        </div>

        <div class="copyright mt-5">
            <div class="row container mx-auto">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <img src="assets/new_images/payment_method.png" alt="">
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4 text-nowrap mb-2">
                    <p>eCommerce @ 2025 All Right Reserved</p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <a href="#"><i class=" fab fa-facebook"></i></a>
                    <a href="#"><i class=" fab fa-instagram"></i></a>
                    <a href="#"><i class=" fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>









    <?php include("layouts/footer.php");?>