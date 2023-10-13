<?php
    include '../../config.php';

if(isset($_POST['delete_data']))
{
    $id = $_POST['id'];
    $event_title = $_POST['event_title'];

        $query = "DELETE FROM `event_tb` WHERE id='$id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
          $sql = "DELETE FROM `schedule_list` WHERE `title` = '$event_title' ";
          $query_runs = mysqli_query($conn, $sql);

          if($query_runs){
            header("Location: ../../dashboard?delete");
          }else{
            header("Location: ../../dashboard?delete");
          }
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }