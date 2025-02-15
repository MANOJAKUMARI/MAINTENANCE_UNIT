<?php
session_start();

include('connection.php');

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $folder = "images/".$file_name;

    $q = mysqli_query($conn,"INSERT INTO equipment (name,category,quantity,images) VALUES ('$name', '$category', '$quantity', '$file_name')");

    if(move_uploaded_file($tempname, $folder)) {
        $_SESSION['success_message'] = "Image uploaded successfully";
        echo "<script>
            alert('Image uploaded successfully');
            window.location.href = window.location.href;
        </script>";
        exit;
    } else {
        echo "Failed to upload image";
    }
}
?>

<!-- Your HTML code here -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add item</title>
    <link rel="stylesheet" href="./style/style.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
    />
  </head>
  <body>
    <!-- navigation -->
    <nav>
      <img src="./assets/logo.png" alt="logo" width="100px" height="100px" />
      <div class="navd1">
        <ul type="none">
          <li>
            <h1>MAINTENANCE UNIT</h1>
            <h2>University of Vavuniya (Admin dashboard)</h2>
           
          </li>
        </ul>
      </div>
      
    </nav>

    <div class="header">
      <!-- Search input -->
      <div class="search-box">
        <img src="./assets/icons8-search.svg" />
        <input type="text" class="search-input" placeholder="Search" />
      </div>

      <!-- Navigation links -->
      <div class="header-links">
        <a href="../admin" >Available</a>
        <a href="../issued" >Issued</a>
        <a href="../pending" >Pending</a>
        <a href="#"class="active" >Add equipment</a>
        <button class ="logoutbtn"><a href="../homeNotLog/index.php">Logout</a></button>
        <!-- <input type="submit" value="Logout" class="logout"> -->
       
      </div>
    </div>

    <div class="container">
        <div class="title">
            <h3>-Add equipment-</h3>
        </div>
        <div class="form">
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="grid">
                    <div class="grid-item">
                        <label for="name">Equipment Name</label><br>
                        <input type="text" name="name" id="name" placeholder="Equipment Name" required>
                    </div>

                    <div class="grid-item">
                        <label for="category">Category</label><br>
                        <select name="category" id="" required>

                            <option value="Gardening">Gardening</option>
                            <option value="Painting">Painting</option>
                            <option value="Carpentry">Carpentry</option>
                            <option value="Electrical">Electrical</option>
                            <option value="other">Other</option>
                        </select>
                        <!-- <input type="text" name="category" id="category" placeholder="Equipment Category" required> -->
                    </div>

                    <div class="grid-item">
                        <label for="quantity">Quantity</label><br>
                        <input type="number" name="quantity" id="quantity" placeholder="Quantity" required>
                    </div>

                    <div>
                        <label for="photos">Browse Photos</label>
                        <input type="file" name="image" id="image" multiple class="photos" required>
                    </div>

                    <div class="sub">
                        <input type="submit" value="Submit" class="submit" name="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- ************** -->

    
    
    </body>
</html>