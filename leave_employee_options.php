<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
<title>Home</title>
<link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
<!-- <link rel="stylesheet" type="text/css" href="sample.css"> -->
</head>
<body>
    <style>
        #multi_option{
	max-width: 100%;
	width: 100%;
}
    </style>

<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['LeaveType'])) {
        $selectedLeaveType = $_POST['LeaveType'];

if (isset($_POST['department'])) {

    
    $selectedDepartment = mysqli_real_escape_string($conn, $_POST['department']);

    if ($selectedDepartment == 'All') {
        $sqla = "SELECT * FROM employee_tb WHERE classification != 3";
        $resulta = mysqli_query($conn, $sqla);
        
        $optionsa = "";
        while ($rowa = mysqli_fetch_assoc($resulta)) {
            

            $EmployeeId = $rowa['empid'];

            $getCredits = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE `col_empID` = '$EmployeeId'");
                    if (mysqli_num_rows($getCredits) > 0) {
                        $rowcredits = $getCredits->fetch_assoc();

                        $creditsForLeaveType = 0;
                        if ($selectedLeaveType === 'Vacation Leave') {
                            $creditsForLeaveType = $rowcredits['col_vctionCrdt'];
                        } elseif ($selectedLeaveType === 'Sick Leave') {
                            $creditsForLeaveType = $rowcredits['col_sickCrdt'];
                        } elseif ($selectedLeaveType === 'Bereavement Leave') {
                            $creditsForLeaveType = $rowcredits['col_brvmntCrdt'];
                        }
                        $optionsa .= '<option value="' . $rowa['empid'] . '">' . $rowa['empid'] . " - " . $rowa['fname'] . " " . $rowa['lname'] . " -  Credits (" . $creditsForLeaveType . ")" . '</option>'; 
                    }else{
                        $optionsa .= '<option>' . $rowa['empid'] . " - " . $rowa['fname'] . " " . $rowa['lname'] . ' - No Credits</option>';   
                    }
        }
        
        echo '<select class="approver-dd" name="empid[]" id="multi_option" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;"> ' . $optionsa . ' </select>';
    } else {
                $sql = "SELECT e.empid, e.fname, e.lname, es.schedule_name
                FROM employee_tb e
                LEFT JOIN empschedule_tb es ON e.empid = es.empid
                WHERE e.department_name = '$selectedDepartment'
                AND e.classification != 3
                AND es.schedule_name IS NOT NULL";

                $result = mysqli_query($conn, $sql);

                $options = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $Empid = $row['empid'];
                    $schedID = $row['schedule_name'];

                    $getCredits = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE `col_empID` = '$Empid'");
                    if (mysqli_num_rows($getCredits) > 0) {
                        $rowcredits = $getCredits->fetch_assoc();

                        $creditsForLeaveType = 0;
                        if ($selectedLeaveType === 'Vacation Leave') {
                            $creditsForLeaveType = $rowcredits['col_vctionCrdt'];
                        } elseif ($selectedLeaveType === 'Sick Leave') {
                            $creditsForLeaveType = $rowcredits['col_sickCrdt'];
                        } elseif ($selectedLeaveType === 'Bereavement Leave') {
                            $creditsForLeaveType = $rowcredits['col_brvmntCrdt'];
                        }
                        $options .= '<option value="' . $row['empid'] . '">' . $row['empid'] . " - " . $row['fname'] . " " . $row['lname'] . " -  Credits (" . $creditsForLeaveType . ")" . '</option>'; 
                    }else{
                        $options .= '<option>' . $row['empid'] . " - " . $row['fname'] . " " . $row['lname'] . ' - No Credits</option>';    
                    }
                       
                }

        echo '<select class="approver-dd" name="empid[]" id="multi_option" multiple  placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;"> ' . $options . ' </select>';
    }
  }
} else {
    echo "Leave Type not provided in the POST data.";
}
} else {
echo "Invalid request method.";
}
?>

<script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_option' 
	});
</script>
</body>
</html>
