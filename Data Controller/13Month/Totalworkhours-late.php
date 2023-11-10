<?php
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
FROM attendances WHERE empid = '$EmployeeID' AND (`status` = 'Present' OR `status` = 'On-Leave') AND `date` IN ('" . implode("','", $dates) . "')");

if($totalattQuery->num_rows > 0){
$attendanceRow = $totalattQuery->fetch_assoc();
$TotalLate = $attendanceRow['total_late'];
$Totalwork = 0; // Add the appropriate value here for 'total_work'
} else {
$TotalLate = 0;
$Totalwork = 0;
}

    
    
?>