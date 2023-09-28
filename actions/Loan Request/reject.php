<?php 
  include '../../config.php';

  if(isset($_POST['reject'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    // echo $status;

    $sql = "UPDATE `payroll_loan_tb` SET `status` = '$status' WHERE `id` = $id";

    $query_run = mysqli_query($conn, $sql);
    if($query_run){
      header("Location: ../../loanRequest");
    
  }
  else{
      echo '<script> alert("Data Not Updated"); </script>';
  }

  }
