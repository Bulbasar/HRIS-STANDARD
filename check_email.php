<?php
// Include your database connection code
// ...


if (isset($_POST['email'])) {
    $email = $_POST['email'];

    echo $email;
    include 'config.php';
    // Perform a query to check if the email exists in your database
    $query = "SELECT * FROM `employee_tb` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Database Error: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($result) > 0) {
            echo '';
        } else {
            echo 'not exists';
        }
    }
}
?>