<?php
// Initialize error array
$errors = array();

if (isset($_POST['Done'])) {
    // Retrieve form data
    $rdate = isset($_POST['rdate']) ? $_POST['rdate'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    // Validate return date
    if (empty($rdate)) {
        $errors['rdate'] = "Return date is required";
    }

    // Validate quantity
    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
        $errors['quantity'] = "Please enter a valid quantity";
    }

    // Check if there are any errors
    if (empty($errors)) {
        // Add your database connection
        require_once('connection.php');

        // Use prepared statements to prevent SQL injection
        $updateQuantityQuery = "UPDATE equipment SET quantity = quantity - ? WHERE name = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuantityQuery);

        if ($stmtUpdate) {
            mysqli_stmt_bind_param($stmtUpdate, 'is', $quantity, $name);
            mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);

            // Get the equipment ID (eid) based on the equipment name
            $getEidQuery = "SELECT eid FROM equipment WHERE name = ?";
            $stmtGetEid = mysqli_prepare($conn, $getEidQuery);

            if ($stmtGetEid) {
                mysqli_stmt_bind_param($stmtGetEid, 's', $name);
                mysqli_stmt_execute($stmtGetEid);
                mysqli_stmt_bind_result($stmtGetEid, $eid);
                mysqli_stmt_fetch($stmtGetEid);
                mysqli_stmt_close($stmtGetEid);

                // Insert values into the 'pending' table
                $insertPendingQuery = "INSERT INTO pending (eid, name, return_date, quantity) VALUES (?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $insertPendingQuery);

                if ($stmtInsert) {
                    mysqli_stmt_bind_param($stmtInsert, 'issi', $eid, $name, $rdate, $quantity);
                    mysqli_stmt_execute($stmtInsert);
                    mysqli_stmt_close($stmtInsert);

                    // If all queries executed successfully, proceed with redirection
                    header("Location: index.php");
                    exit();
                } else {
                    // Handle errors if the prepared statement for insertion fails
                    echo "Error in prepared statement for insertion: " . mysqli_error($conn);
                }
            } else {
                // Handle errors if the prepared statement for getting eid fails
                echo "Error in prepared statement for getting eid: " . mysqli_error($conn);
            }
        } else {
            // Handle errors if the prepared statement for updating fails
            echo "Error in prepared statement for updating: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>

    <link rel="stylesheet" href="./assets/style1.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>

<div class="container">

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="back">
            <input type="submit" name="back" value="< Back">
        </div>
    </form>

    <div class="title">
        <h3>-Booking Equipment-</h3>
    </div>

    <div class="card">
        <?php
        if(isset($_POST['booking'])){
            $image_url = $_POST['image_url'];
            echo '<img src="' . $image_url . '" alt="Equipment Image">';
        }
        ?>
        <div class="name">
            <?php
            if(isset($_POST['booking'])){
                $name=$_POST['name'];
                echo '<h3>'.$name. '</h3>';
            }
            ?>
        </div>
    </div>

    <div class="form">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Form fields -->
        <input type="hidden" name="name" value="<?php echo $name; ?>"> <!-- Add this line for the equipment name -->
        <div class="inp">
            <label for="rdate">Return date</label><br>
            <input type="date" name="rdate" id="rdate" value="<?php echo $rdate; ?>"><br>
            <!-- Display return date error -->
            <?php if (isset($_POST['Done']) && isset($errors['rdate'])) { ?>
                <p class="error-message"><?php echo $errors['rdate']; ?></p>
            <?php } ?>
        </div>

        <div class="inp">
            <label for="quantity">Quantity</label><br>
            <input type="number" name="quantity" id="quantity" value="<?php echo $quantity; ?>"><br>
            <!-- Display quantity error -->
            <?php if (isset($_POST['Done']) && isset($errors['quantity'])) { ?>
                <p class="error-message"><?php echo $errors['quantity']; ?></p>
            <?php } ?>
        </div>

        <div class="book">
            <input type="submit" name="Done" value="Done">
        </div>
    </form>
    </div>

</div>

<?php
if(isset($_POST['back'])){
    header("Location: index.php");
}
?>

</body>

</html>
