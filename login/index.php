<?php
session_start();
require_once('connection.php');

// Check for form submission
if (isset($_POST['login'])) {

    $errors = array();

    // Check if the username and password have been entered
    if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
        $errors[] = "Email is missing or invalid";
    }

    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
        $errors[] = "Password is missing or invalid";
    }

    // Check for errors in the form
    if (empty($errors)) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the user is an admin
        $adminQuery = "SELECT * FROM admin WHERE email = '{$email}' AND password ='{$password}'";
        $adminResult = mysqli_query($conn, $adminQuery);

        if ($adminResult) {
            if (mysqli_num_rows($adminResult) == 1) {
                // Valid admin found, redirect to admin page
                $_SESSION['user_name'] = 'Admin';
                header('location: ../admin');
                exit();
            }
        } else {
            $errors[] = "Database query failed";
        }

        // If not an admin, check regular user
        $userQuery = "SELECT * FROM user WHERE email = '{$email}' AND password ='{$password}'";
        $userResult = mysqli_query($conn, $userQuery);

        if ($userResult) {
            if (mysqli_num_rows($userResult) == 1) {
                // Valid user found, redirect to user page
                $user = mysqli_fetch_assoc($userResult);
                $_SESSION['user_name'] = $user['user_name'];
                header('location: ../homeLoged/index.php');
                exit();
            } else {
                $errors[] = "Invalid username or password";
            }
        } else {
            $errors[] = "Database query failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./style/style.css">
  <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
    />
</head>
<body>
  <div class="maindiv">

          <div class="container1">
                <div class="logo">
                    <img src="assets/logo.png" alt="" width="150px" height="150px" >
                </div>
                
                <div class="title1">

                    <h1>MAINTENANCE UNIT</h1>
                    <h3 class="title3">University of Vavuniya</h3>
                    <h3 class="title2">-Login-</h3>
                    
                </div>

                <div class="form">

                  <form action="index.php" method="post">

                    <?php
                          if (!empty($errors)) {
                          
                            echo"<p class='error'>Invalid username or password</p>";
                          }
                          if (isset($_GET['logout'])) {
                          
                            echo"<p class='info'>You have succesfully loged out</p>";
                          }
                          ?>
                      
                      <div class="input">

                          <div>
                              
                                <label for="email">email</label><br>
                                <input type="text" name="email" id="email" placeholder="email"><br><br>
                              
                          </div>
                          
                          <div>
                          
                            <label for="password">Password</label><br>
                            <input type="password" name="password" id="password"placeholder="Password"><br><br>
                          

                          </div>

                          <div class="forgot">
                          <p>Forgot Password <a class="link" href="../resetPassword/index.php">Click here</a></p>
                            </div>

                      </div>

                      <div class="submit">
                              
                          <input type="submit" value="login" name="login">

                      </div>

                      <div class="singup">
                      <a class="link" href="../signup/index.php">you don't have an account</a>
                      </div>


                    </form>

                            <!-- <div class="submit">
                              
                                  <input type="submit" value="login">

                            </div> -->
                           
                </div>

          </div>

          
  </div>



        

  

</body>
</html>