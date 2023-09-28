
<?php
session_start();
//    $empid = $_SESSION['empid'];
   if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} else {
    // Check if the user's role is not "admin"
    if ($_SESSION['role'] != 'admin') {
        // If the user's role is not "admin", log them out and redirect to the logout page
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit();
    } else{
        include 'config.php';
        $userId = $_SESSION['empid'];
       
        $iconResult = mysqli_query($conn, "SELECT id, emp_img_url, empid FROM employee_tb WHERE empid = '$userId'");
        $iconRow = mysqli_fetch_assoc($iconResult);

        if ($iconRow) {
            $image_url = $iconRow['emp_img_url'];
        } else {
            // Handle the case when the user ID is not found in the database
            $image_url = '../img/user.jpg'; // Set a default image or handle the situation accordingly
        }
    
    }
}
    
include 'config.php';

    // if(isset($_POST['submit'])){
    //     $empid = $_POST['empid'];
    //     $schedule_name = $_POST['schedule_name'];
    //     $sched_from = $_POST['sched_from'];
    //     $sched_to = $_POST['sched_to'];

    //     // $schedule_name = mysqli_real_escape_string($conn, $empid);
    //     // $schedule_name = mysqli_real_escape_string($conn, $schedule_name);
    //     // $schedule_name = mysqli_real_escape_string($conn, $sched_from);
    //     // $schedule_name = mysqli_real_escape_string($conn, $sched_to);

    //     $sql = "UPDATE empschedule_tb
    //             SET schedule_name ='$schedule_name', sched_from ='$sched_from', sched_to ='$sched_to'
    //             WHERE empid='$empid'";

    //     if(mysqli_query($conn, $sql)){
    //         header("Location: Schedules.php");
    //     } else{
    //         echo "Error updating record: ".mysqli_error($conn);
    //     }
    // }
    //     $result = mysqli_query($conn, "SELECT * FROM empschedule_tb WHERE empid ='".$_GET['empid']."' ");
    //     $row = mysqli_fetch_assoc($result);
   

    if(count($_POST) > 0){
        mysqli_query($conn, "UPDATE empschedule_tb 
                             SET schedule_name='".$_POST['schedule_name']."', 
                             sched_from='".$_POST['sched_from']."', 
                             sched_to='".$_POST['sched_to']."' 
                             WHERE empid='".$_POST['empid']."'");

        header("Location: Schedules.php");
    }
        $result = mysqli_query($conn, "SELECT * FROM empschedule_tb WHERE empid ='".$_GET['empid']."'");
        $row = mysqli_fetch_assoc($result);

        // $result = mysqli_query($conn, "SELECT * FROM empschedule_tb
        // AS esched
        // INNER JOIN employee_tb
        // AS emp
        // ON(esched.empid = emp.empid)");

        // $result = mysqli_query($conn, "SELECT * FROM employee_tb
        // AS emp
        // INNER JOIN empschedule_tb
        // AS esched
        // ON(emp.empid = esched.empid)");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
      
      include 'configHardware.php';
      
      
      ?>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css"> 
    <title>HRIS | Employee List</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>

    

    <form action="" method="POST">
    <div class="schedules-modal-update" id="schedules-modal-update">
        <div class="sched-container">
            <div class="sched-content">
                <div class="schedmodal-title">
                <h1>Update Schedule</h1>
                <div></div>
                </div>
                <div class="schedmodal-emp">
                <input type="hidden" name="empid" value="<?php echo $row['empid']; ?>">
                    
                       <h1><span style="margin-right:10px;"><?php echo $row['empid'] ?></span></h1>
                </div>
                <div class="schedule-type-update">
                <?php
                    include 'config.php';
                    $sql = "SELECT schedule_name FROM schedule_tb";
                    $results = mysqli_query($conn, $sql);

                    $options = "";
                        while ($rows = mysqli_fetch_assoc($results)) {
                            $options .= "<option value=' ". $rows['schedule_name'] . "'>" .$rows['schedule_name']."</option>";
                        }
                        ?>

                    <label for="schedule_name">Schedule Type</label><br>
                    <select name="schedule_name" id=""  value="<?php echo $row['schedule_name'];?>">
                        <option value disabled selected>Select Schedule Type</option>
                        <?php echo $options; ?>
                    </select>
                </div>
                <div class="sched-update-date">
                <label for="sched_from">From</label>
                <input type="date" name="sched_from" id="" value="<?php echo $row['sched_from'];?>">

                <label for="sched_to">To</label>
                <input type="date" name="sched_to" id="" value="<?php echo $row['sched_to'];?>">
                <div>
                
                <div class="sched-update-btn">
                <button value="Cancel" id="sched-update-close" class="sched-update-close"><a href="Schedules.php"> Close </a></button>
                <button value="update" name="update" type="submit">Update</button>
                </div>
                
            </div>
        </div>
    </div>
    </form>
    

<script>
// // sched form modal

// let Modal = document.getElementById('schedules-modal-update');

// //get open modal
// let modalBtn = document.getElementById('sched-update');

// //get close button modal
// let closeModal = document.getElementsByClassName('sched-update-close')[0];

// //event listener
// modalBtn.addEventListener('click', openModal);
// closeModal.addEventListener('click', exitModal);
// window.addEventListener('click', clickOutside);

// //functions
// function openModal(){
//     Modal.style.display ='block';
// }

// function exitModal(){
//     Modal.style.display ='none';
// }

// function clickOutside(e){
//     if(e.target == Modal){
//         Modal.style.display ='none';    
//     }
// }
</script>


    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="main.js"></script>
</body>
</html>