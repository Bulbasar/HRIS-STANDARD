<?php
// Retrieve the form data
$date = $_POST['date']; // Make sure to update this with the correct form input name
$typeHoliday = $_POST['type_holiday'];
$id = $_POST['id_holiday'];
$holiday_title = $_POST['holiday_title'];

require('../../config.php');

// echo $holiday_title;

// SQL query to update data in the database
$sql = "UPDATE `holiday_tb` SET `date_holiday`= '$date',`holiday_type`= '$typeHoliday' WHERE `id` = '$id'";

$updateCalender = "UPDATE `schedule_list` SET `start_datetime` = '$date', `end_startdate` = '$date' , `description` = '$typeHoliday' WHERE `title` = '$holiday_title'  ";

// $query_run = mysqli_query($conn, $updateCalender);

if ($conn->query($sql) === TRUE) {
    // If the update was successful, retrieve the updated row from the database
    $select_query = "SELECT * FROM holiday_tb WHERE `id` = '$id'";
    $select_result = mysqli_query($conn, $select_query);

    // Fetch the updated row as an associative array
    $updated_row = mysqli_fetch_assoc($select_result);

    // Return the updated row as JSON
    echo json_encode($updated_row);
} else {
    // If there was an error during update, send an error response
    echo 'Error: ' . $sql . '<br>' . $conn->error;
}

// Close the connection
$conn->close();
?>
