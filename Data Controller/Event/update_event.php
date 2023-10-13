<?php
include '../../config.php';

    if(isset($_POST['update_event'])){

        $id = $_POST['id'];
        $event_title = $_POST['event_title'];
        $event_type = $_POST['event_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $sql = "UPDATE `event_tb` SET `event_title` = '$event_title', `event_type` = '$event_type', `start_date` = '$start_date', `end_date` = '$end_date' WHERE `id` = $id ";
        $query_run = mysqli_query($conn, $sql);

        if($query_run){
            $sql = "UPDATE `schedule_list` SET `title` = '$event_title', `description` = '$event_type', `start_datetime` = '$start_date', `end_datetime` = '$end_date' WHERE `title` = '$event_title' ";
            $query_runs = mysqli_query($conn, $sql);

            if($query_runs){
                header("Location: ../../dashboard?updated");
            }else{
                header("Location: ../../dashboard?updated");
            }
            // header("Location: ../../Piece_rate");
          
        }
        else{
            echo '<script> alert("Data Not Updated"); </script>';
        }

    }