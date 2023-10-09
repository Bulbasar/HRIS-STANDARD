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
    ) AS total_late,
    -- CONCAT(
    --     FLOOR(
    --         SUM(
    --             TIME_TO_SEC(attendances.early_out)
    --         ) / 3600
    --     ),
    --     'H:',
    --     FLOOR(
    --         (
    --             SUM(
    --                 TIME_TO_SEC(attendances.early_out)
    --             ) % 3600
    --         ) / 60
    --     ),
    --     'M'
    -- ) AS total_undertime,
    CONCAT(
        FLOOR(
            SUM(
                TIME_TO_SEC(attendances.total_work)
            ) / 3600
        ),
        'H:',
        FLOOR(
            (
                SUM(
                    TIME_TO_SEC(attendances.total_work)
                ) % 3600
            ) / 60
        ),
        'M'
    ) AS total_work
    FROM attendances WHERE empid = '$EmployeeID' AND (`status` = 'Present' OR `status` = 'On-Leave') AND `date` BETWEEN '$str_date' AND '$end_date'");

    if($totalattQuery->num_rows > 0){
        $attendanceRow = $totalattQuery->fetch_assoc();
        $TotalLate = $attendanceRow['total_late'];
        // $TotalUndertime = $attendanceRow['total_undertime'];
        $Totalwork = $attendanceRow['total_work'];
    } else {
        $TotalLate = 0;
        $Totalwork = 0;
    }
    
    
?>