<?php

    session_start();

    include('server/connection.php');

    $usernameRegex = "/^[a-zA-Z\s]{2,50}$/";
    $passwordRegex = "/^.{6,}$/";
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";  
    $logFilePath = 'loggerFiles/registration_attempts.log';

    if(isset($_SESSION['logged_in'])){
        header('location: account.php');
        exit;
     }
     
 if(isset($_POST['register'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password']);

    $name = htmlspecialchars($name);//cross site scripting.

    if (preg_match($usernameRegex,$name) && preg_match($passwordRegex, $password) && preg_match($emailRegex, $email)){
    //1.checks the passwords match with confirm password.
    if($password !== $confirmPassword){
        $logEntry = date("Y-m-d H:i:s") . " - ERROR - Username: $username, Registration Status: FAILURE, Reason: passwords dont match, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
        header('location: register.php?error=passwords dont match');
    }
    //2.checks the password less than 6 characters.
    else if(strlen($password) < 6){
        $logEntry = date("Y-m-d H:i:s") . " - ERROR - Username: $username, Registration Status: FAILURE, Reason: password must be at least 6 characters, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
        header('location: register.php?error=password must be at least 6 characters');
    }

    //there is no error below code will run.
    else{
    
            //check whether there is a user with this email or not.
            $stmt1 = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email=?");
            $stmt1->bind_param('s',$email);
            $stmt1->execute();
            $stmt1->bind_result($num_rows);
            $stmt1->store_result();
            $stmt1->fetch();

            //if there is a user already registered with this email
            if($num_rows != 0){
                $logEntry = date("Y-m-d H:i:s") . " - ERROR - Email: $email, Registration Status: FAILURE, Reason: user with this email already exists, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
                        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
                header('location: register.php?error=user with this email already exists');
            }
            else{

                        //create a new user
                    $stmt = $conn->prepare("INSERT INTO users (user_name,user_email,user_password) VALUES (?,?,?)");

                    $stmt->bind_param('sss',$name,$email,md5($password)); //md5 hash the password


                    //if account was created successfully
                    if($stmt->execute()){
                        $user_id = $stmt->insert_id;
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_name'] = $name;
                        $_SESSION['logged_in'] = true;

                        header('location: account.php?register_success=You registered successfully');
                    
                    //account could not be created
                    }else{
                        $logEntry = date("Y-m-d H:i:s") . " - ERROR - Username: $name, Registration Status: FAILURE, Reason: Could not create an account at the moment., IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
                        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
                            header('location: register.php?error=Could not create an account at the moment.');
                    }
                  }
            } 
     }else{
        $logEntry = date("Y-m-d H:i:s") . " - ERROR - Username: $name, Registration Status: FAILURE, Reason: Invalid Input, IP Address: " . $_SERVER['REMOTE_ADDR'] . ", User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        file_put_contents($logFilePath, $logEntry, FILE_APPEND);
        header('location: register.php?error=Invalid Input.');
     }
  //if user has already registered, then take user to account page
    
 }



?>



<?php include("layouts/header.php");?>

    <!--Register-->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Register</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form action="register.php" id="register-form" method="POST">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" class="form-control" id="register-email" name="email" placeholder="Email"
                        required>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control" id="register-password" name="password"
                        placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="">Confirm Password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirm-password"
                        placeholder="Confirm Password" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="register" class="btn" id="register-btn" value="Register">
                </div>
                <div class="form-group">
                    <a href="login.php" id="login-url" class="btn">Do you have an account? Login</a>
                </div>
            </form>
        </div>

    </section>

    <?php include("layouts/footer.php");?>