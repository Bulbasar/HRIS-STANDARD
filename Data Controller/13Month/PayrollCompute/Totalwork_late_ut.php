<?php
$TotalLates = 0;
$totalattQuery = mysqli_query($conn, "SELECT 
    CONCAT(
        FLOOR(
            SUM(
                TIME_TO_SEC(attendances.late)
            ) / 3600
        ),
        'H:',
        FLOOR(
            (
                SUM(
                    TIME_TO_SEC(attendances.late)
                ) % 3600
            ) / 60
        ),
        'M'
    ) AS total_late
    FROM attendances WHERE empid = '$EmployeeID' AND (`status` = 'Present' OR `status` = 'On-Leave') AND `date` BETWEEN '$str_date' AND '$end_date'");

    if($totalattQuery->num_rows > 0){
        $attendanceRows = $totalattQuery->fetch_assoc();
        $TotalLates = $attendanceRows['total_late'];
    } else {
        $TotalLates = 0;
    }
    
    
?>