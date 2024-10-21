<?php

session_start();

include('server/connection.php');

$emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";  
$passwordRegex = "/^.{6,}$/";
$logFilePath = 'loggerFiles/login_attempts.log';
$max_attempts = 3;
$lockout_time = 60; // 60 seconds lockout period

// Initialize failed login attempts if not set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    if (time() < $_SESSION['lockout_time']) {
        header('location: login.php?error=Your account is locked. Please try again later.');
        exit();
    } else {
        // Reset lockout
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_time']);
    }
}

if(isset($_SESSION['logged_in'])){
    header('location: account.php');
    exit;
}

if(isset($_POST['login_btn'])){

    $email = $_POST['email'];
    $password = md5($_POST['password']);

     // Calculate time difference since last login attempt
     $time_since_last_attempt = time() - $_SESSION['last_attempt_time'];

     if ($time_since_last_attempt < $lockout_time && $_SESSION['failed_attempts'] >= $max_attempts) {
         // User is locked out
         header('location: login.php?error=Too many failed attempts. Please try again after 60 seconds.');
     } else {
         if ($time_since_last_attempt >= $lockout_time) {
             // Reset failed attempts after lockout time passes
             $_SESSION['failed_attempts'] = 0;
         }
 
 

    if (preg_match($emailRegex, $email) && preg_match($passwordRegex, $password)) {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
        
        $secretKey='6Lc_9GIqAAAAADVFzYf5KxGZapNP_jwUoomZQBBU';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
        $response = json_decode($verifyResponse);


        if($response->success){
                    $stmt = $conn->prepare("SELECT user_id,user_name,user_email,user_password FROM users WHERE user_email = ? AND user_password = ? LIMIT 1");

                    $stmt->bind_param('ss',$email,$password);

                    if($stmt->execute()){
                    $stmt->bind_result($user_id,$user_name,$user_email,$user_password);
                    $stmt->store_result();

                    if($stmt->num_rows() == 1){
                        $stmt->fetch();
                        
                        $_SESSION['failed_attempts'] = 0;
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_name'] = $user_name;
                        $_SESSION['user_email'] = $user_email;
                        $_SESSION['logged_in'] = true;

                       $logEntry = date("Y-m-d H:i:s") . " - INFO - Email: $email, Login Status: SUCCESS, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
                       file_put_contents($logFilePath, $logEntry, FILE_APPEND);
                       header('location: account.php?login_success=logged in successfully ');
        }else{
              
                        

            $logEntry = date("Y-m-d H:i:s") . " - ERROR - Email: $email, Login Status: FAILURE, Reason: something went wrong, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
            file_put_contents($logFilePath, $logEntry, FILE_APPEND);
            header('location: login.php?error= something went wrong');

            
        }
        }else{



            $logEntry = date("Y-m-d H:i:s") . " - ERROR - Email: $email, Login Status: FAILURE, Reason: veirfy captcha, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
            file_put_contents($logFilePath, $logEntry, FILE_APPEND);
            header('location: login.php?error=veirfy captcha.');
        }

        }else{
                    
          

          
            $logEntry = date("Y-m-d H:i:s") . " - ERROR - Email: $email, Login Status: FAILURE, Reason: could not verify your account, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
            file_put_contents($logFilePath, $logEntry, FILE_APPEND);
            header('location: login.php?error=could not verify your account');
        }

    }else{
        //error
        $logEntry = date("Y-m-d H:i:s") . " - ERROR - Email: $email, Login Status: FAILURE, Reason: Something went wrong, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
            file_put_contents($logFilePath, $logEntry, FILE_APPEND);
        header('location: login.php?error=Something went wrong');
    }
}else{
     // Record failed login attempt
     $_SESSION['failed_attempts']++;
     $_SESSION['last_attempt_time'] = time();

     


    $logEntry = date("Y-m-d H:i:s") . " - ERROR - Email: $email, Login Status: FAILURE, Reason: Invalid input, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    file_put_contents($logFilePath, $logEntry, FILE_APPEND);
    header('location: login.php?error=Invalid username or password. Attempts left: '.($max_attempts - $_SESSION['failed_attempts']));
}
}

}

?>





<?php include("layouts/header.php");?>

    <!--Login-->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Login</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form action="login.php" method="POST" id="login-form">
                <p style="color: red" class="text-center"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control" id="login-password" name="password"
                        placeholder="password" required>
                </div>
                <div class="form-group">
                <div class="g-recaptcha" style="display: inline-block; text-align: center;" data-sitekey="6Lc_9GIqAAAAAALNzFTyf4V3IP8t6aU9mlvStU_n" data-callback="enableLoginBtn"></div>
                </div>
                <div class="form-group" >
                    <input type="submit" class="btn"  id="login-btn" name="login_btn" value="Login" ><!--disabled="disabled"-->
                </div>
                <div class="form-group">
                    <a href="register.php" id="register-url" class="btn">Don't have account? Register</a>
                </div>
            </form>
        </div>

    </section>
    <script>
    function enableLoginBtn(){
      document.getElementById("login-btn").disabled = false;
    }
  </script>

    <?php include("layouts/footer.php");?>