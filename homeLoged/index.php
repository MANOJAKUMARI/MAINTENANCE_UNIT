<?php
require_once 'connection.php';
$q = "SELECT * FROM equipment";
$result = $conn->query($q);
?>
<?php
session_start();

if (!isset($_SESSION['user_name'])) {
  header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home page</title>
  <link rel="stylesheet" href="./assets/style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
  <!-- navigation -->
  <nav>
    <img src="./assets/logo.png" alt="logo" width="100px" height="100px" />
    <div class="navd1">
      <ul type="none">
        <li>
          <h1>MAINTENANCE UNIT</h1>
          <h2>University of Vavuniya</h2>
        </li>
      </ul>
    </div>
    <div class="navd2">
      <h4>Welcome! <?php echo $_SESSION['user_name']; ?></h4>
      <button class="logoutbtn"><a href="../homeNotLog/index.php">Logout</a></button>
    </div>
  </nav>

  <div class="header">
    <!-- Search input -->
    <form id="search-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
      <div class="search-box">
        <img src="./assets/icons8-search.svg" />
        <input type="text" class="search-input" name="search" placeholder="Search" />
      </div>
    </form>

    <!-- Navigation links -->
    <div class="header-links">
      <a href="#">Home</a>
      <a href="../website/about/about.php">About</a>
      <a href="../website/contact/contactus.php">Contact</a>
      <a href="../website/category/category.php">Category</a>
    </div>
  </div>

  <!-- hero -->
  <div class="hero"></div>

  <!-- content -->
  <?php
  require_once('./connection.php');

  // Check if the search form is submitted
  if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // Modify the query to search for relevant items
    $q = "SELECT * FROM equipment WHERE name LIKE '%$search%'";
    $result = mysqli_query($conn, $q);
    $check = mysqli_num_rows($result) > 0;

    if ($check) {
      // Display the relevant items
      while ($row = mysqli_fetch_assoc($result)) {
        // Display the item details
        // ...
      }
    } else {
      echo "No data found";
    }
  }
  ?>

  <div class="container-1">
    <?php
    require_once('./connection.php');

    $q = "SELECT * FROM equipment";
    $result = mysqli_query($conn, $q);

    $check = mysqli_num_rows($result) > 0;

    if ($check) {
      $counter = 0; // Counter to keep track of images per row

      while ($row = mysqli_fetch_assoc($result)) {
        if ($counter % 5 == 0) {
          // Start a new row for every 5 images
          echo '<div class="row">';
        }
        ?>
        <div class="col-md-2"> <!-- Adjust the column width as needed -->
          <div class="card">
            <div class="image">
              <img src="../additem/images/<?php echo $row['images']; ?>" class="card-img-top" alt="eq images">
            </div>

            <form action="booking.php" method="POST">
              <div class="caption">
                <h3 class="card-title"><?php echo $row['name']; ?></h3>
                <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
                <input type="hidden" name="image_url" value="../additem/images/<?php echo $row['images']; ?>">
              </div>

              <div class="count">
                <?php
                $availableQuantity = $row['quantity'];
                echo 'Available: ' . $availableQuantity . ' Items';

                // Check if quantity is greater than 0 to display the booking button
                if ($availableQuantity > 0) {
                  echo '<input type="submit" value="Booking" class="btn" name="booking">';
                } else {
                  echo '<p class="not-available"><b>Not available</b></p>';
                }
                ?>
              </div>
            </form>
          </div>
        </div>
        <?php
        $counter++;
        if ($counter % 5 == 0) {
          // Close the row after every 5 images
          echo '</div>';
        }
      }

      // Close the row if the number of images isn't a multiple of 5
      if ($counter % 5 != 0) {
        echo '</div>';
      }
    } else {
      echo "No data found";
    }
    ?>
  </div>


    <!-- footer -->
  <section>
      <footer>
        <div class="footer">
          <div class="grid-element">
            <div class="logo">
              <h3>MAINTENANCE UNIT</h3>
              <p>University of Vavuniya</p>
            </div>
          </div>

          <div class="grid-element">
            <p class="title">Contact us</p>
            <br />
            <a href="#" class="el">maintenanceunituov@vau.com</a><br />
            <a href="#" class="el">071-4569872</a><br />
            <a href="#" class="el">071-4569873</a>
          </div>

          <div class="grid-element">
            <p class="title">Location</p>
            <br />
            <a class="el"
              >University of Vavuniya <br />
              Vavuniya, Sri Lanka</a>
            <br /> 

            
          </div>

          <div class="grid-element">
            <p class="title">Quick links</p>
            <br />
            <a href="#" class="el">Home</a><br />
            <a href="../Website/about/about.php" class="el">About</a><br />
            <a href="../Website/contact/contactus.php" class="el">Contact</a><br />
          </div>
        </div>

        <div class="coppy-right">
          <hr />
          <br />
          <p>© 2023. All Rights Reserved</p>
        </div>
      </footer> 
    </section>

    <?php
    
    ?>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.querySelector("#search-form");

    searchForm.addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent the default form submission
      const searchInput = document.querySelector(".search-input");
      const searchValue = searchInput.value.trim(); // Trim whitespace from the search value
      
      // Check if the search value is not empty
      if (searchValue !== '') {
        searchForm.submit();
      }
    });
  });
</script>
  </body>
</html>
