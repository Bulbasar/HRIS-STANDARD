<?php
include '../../config.php';

if(isset($_POST['add_holiday'])){

    $createBy = mysqli_real_escape_string($conn, $_POST['name_emp']);
    $holiday_title = mysqli_real_escape_string($conn, $_POST['title_holiday']);
    $holidayDate = mysqli_real_escape_string($conn, $_POST['date_holiday']);
    $typeofHoliday = mysqli_real_escape_string($conn, $_POST['type_holiday']);

    // echo $holidayDate;

    $sql = "SELECT * FROM schedule_list WHERE `start_datetime` = '$holidayDate' ";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        header("Location: ../../Calendar?duplicate");
        // echo "duplicate";
    }else{
        $query = "INSERT INTO holiday_tb (`empid`, `holiday_title`, `date_holiday`, `holiday_type`)
        VALUES ('$createBy', '$holiday_title', '$holidayDate', '$typeofHoliday')";

        $query_run = mysqli_query($conn, $query);

        if($query_run)
        {
            $sql = "INSERT INTO schedule_list (`title`, `description`, `start_datetime`, `end_datetime`)
                    VALUES ('$typeofHoliday', '$holiday_title', '$holidayDate', '$holidayDate' ) ";

            $sql_run = mysqli_query($conn, $sql);
            header("Location: ../../Calendar?inserted");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }

}
?>