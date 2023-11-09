<?php
$empschedQuery = mysqli_query($conn, "SELECT * FROM empschedule_tb WHERE `empid` = '$EmployeeID'");
if($empschedQuery->num_rows > 0){
    $empschedRow = $empschedQuery->fetch_assoc();
    $schedulename = $empschedRow['schedule_name'];

    $schedQuery = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedulename'");
    if($schedQuery->num_rows > 0){
        $row_Sched = $schedQuery->fetch_assoc();
    }else{
        echo "No results found schedule.";
    }
}else{
    echo "No results found.";
}  

// -----------------------SCHED MONDAY START----------------------------//
if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){                         
    $MON_timeIN = '00:00:00';
    $MON_timeOUT = '00:00:00'; 

    // convert ang time na nakastring sa DateTime
    $MondaytimeIN = new DateTime($MON_timeIN);
    $MondaytimeOUT = new DateTime($MON_timeOUT);
    
    $timeDifference = $MondaytimeOUT->diff($MondaytimeIN);
    $MOn_total_work = $timeDifference->format('%H:%I:%S');

    // // naconvert ko ang value ng $totalSeconds from time to integer
    // $totalSeconds = $timeDifference->s + ($timeDifference->i * 60) + ($timeDifference->h * 3600);
    // $MOn_total_work = (int)$totalSeconds;
    
}else{
    $MON_timeIN = $row_Sched['mon_timein'];
    $MON_timeOUT = $row_Sched['mon_timeout'];

    // Create a DateTime object from the string
    $mon_timeIN_object = DateTime::createFromFormat('H:i', $MON_timeIN);
    $mon_timeIN_formatted = $mon_timeIN_object->format('H:i'); 
    list($mon_hours, $mon_minutes) = explode(':', $mon_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $mon_total_minutes_timein = $mon_hours + $mon_minutes;

    $mon_timeout_object = DateTime::createFromFormat('H:i', $MON_timeOUT);
    $mon_timeout_formatted = $mon_timeout_object->format('H:i'); 
    list($mon_hourss, $mon_minutess) = explode(':', $mon_timeout_formatted);

    // Convert hours and minutes to total minutes
    $mon_total_minutes_timeout = $mon_hourss + $mon_minutess;

    $mon_total_minutes_timein = intval($mon_total_minutes_timein);
    $mon_total_minutes_timeout = intval($mon_total_minutes_timeout);
       
    if($mon_total_minutes_timeout > $mon_total_minutes_timein){
        $MOn_total_work = ($mon_total_minutes_timeout - $mon_total_minutes_timein) - 1;
    }else{
        $MOn_total_work = ($mon_total_minutes_timein - $mon_total_minutes_timeout) - 1;
    }
}

// -----------------------SCHED TUESDAY START----------------------------//
if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
    $tue_timeIN = '00:00:00';
    $tue_timeout = '00:00:00';

    $TuesdaytimeIN = new DateTime($tue_timeIN);
    $TuesdaytimeOUT = new DateTime($tue_timeout);
    
    $timeDifference = $TuesdaytimeOUT->diff($TuesdaytimeIN);
    $Tue_total_work = $timeDifference->format('%H:%I:%S');
}else{
    $tue_timeIN = $row_Sched['tues_timein'];
    $tue_timeout = $row_Sched['tues_timeout'];
    
    $tue_timeIN_object = DateTime::createFromFormat('H:i', $tue_timeIN);
    $tue_timeIN_formatted = $tue_timeIN_object->format('H:i'); 
    list($tue_hours, $tue_minutes) = explode(':', $tue_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $tue_total_minutes_timein = $tue_hours + $tue_minutes;

    $tue_timeout_object = DateTime::createFromFormat('H:i', $tue_timeout);
    $tue_timeout_formatted = $tue_timeout_object->format('H:i'); 
    list($tue_hourss, $tue_minutess) = explode(':', $tue_timeout_formatted);

    // Convert hours and minutes to total minutes
    $tue_total_minutes_timeout = $tue_hourss + $tue_minutess;

    $tue_total_minutes_timein = intval($tue_total_minutes_timein);
    $tue_total_minutes_timeout = intval($tue_total_minutes_timeout);

    if($tue_total_minutes_timeout > $tue_total_minutes_timein){
        $Tue_total_work = ($tue_total_minutes_timeout - $tue_total_minutes_timein) - 1;
    }else{
        $Tue_total_work = ($tue_total_minutes_timein - $tue_total_minutes_timeout) - 1;
    }
}

// -----------------------SCHED WEDNESDAY START----------------------------//
if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
    $wed_timeIN = '00:00:00';
    $wed_timeout = '00:00:00';

    $WednesdaytimeIN = new DateTime($wed_timeIN);
    $WednesdaytimeOUT = new DateTime($wed_timeout);

    $timeDifference = $WednesdaytimeOUT->diff($WednesdaytimeIN);
    $wed_total_work = $timeDifference->format('%H:%I:%S');
}else{
    $wed_timeIN = $row_Sched['wed_timein'];
    $wed_timeout = $row_Sched['wed_timeout'];

    $weds_timeIN_object = DateTime::createFromFormat('H:i', $wed_timeIN);
    $weds_timeIN_formatted = $weds_timeIN_object->format('H:i'); 
    list($weds_hours, $weds_minutes) = explode(':', $weds_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $weds_total_minutes_timein = $weds_hours + $weds_minutes;

    $weds_timeout_object = DateTime::createFromFormat('H:i', $wed_timeout);
    $weds_timeout_formatted = $weds_timeout_object->format('H:i'); 
    list($weds_hourss, $weds_minutess) = explode(':', $weds_timeout_formatted);

    // Convert hours and minutes to total minutes
    $weds_total_minutes_timeout = $weds_hourss + $weds_minutess;

    $weds_total_minutes_timein = intval($weds_total_minutes_timein);
    $weds_total_minutes_timeout = intval($weds_total_minutes_timeout);

    $wed_total_work = ($weds_total_minutes_timeout - $weds_total_minutes_timein) - 1;

    if($weds_total_minutes_timeout > $weds_total_minutes_timein){
        $wed_total_work = ($weds_total_minutes_timeout - $weds_total_minutes_timein) - 1;
    }else{
        $wed_total_work = ($weds_total_minutes_timein - $weds_total_minutes_timeout) - 1;
    }
}

// -----------------------SCHED THURSDAY START----------------------------//
if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                                       
    $thurs_timeIN = '00:00:00';
    $thurs_timeout = '00:00:00';

    $ThursdaytimeIN = new DateTime($thurs_timeIN);
    $ThursdaytimeOUT = new DateTime($thurs_timeout);

    $timeDifference = $ThursdaytimeOUT->diff($ThursdaytimeIN);
    
    $thurs_total_work = $timeDifference->format('%H:%I:%S');                                     
}else{
    $thurs_timeIN = $row_Sched['thurs_timein'];
    $thurs_timeout = $row_Sched['thurs_timeout'];
    
    // Create a DateTime object from the string
    $thurs_timeIN_object = DateTime::createFromFormat('H:i', $thurs_timeIN);
    $thurs_timeIN_formatted = $thurs_timeIN_object->format('H:i'); 
    list($thurs_hours, $thurs_minutes) = explode(':', $thurs_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $thurs_total_minutes_timein = $thurs_hours + $thurs_minutes;


    $thurs_timeout_object = DateTime::createFromFormat('H:i', $thurs_timeout);
    $thurs_timeout_formatted = $thurs_timeout_object->format('H:i'); 
    list($thurs_hourss, $thurs_minutess) = explode(':', $thurs_timeout_formatted);

    // Convert hours and minutes to total minutes
    $thurs_total_minutes_timeout = $thurs_hourss + $thurs_minutess;

    $thurs_total_minutes_timein = intval($thurs_total_minutes_timein);
    $thurs_total_minutes_timeout = intval($thurs_total_minutes_timeout);

    if($thurs_total_minutes_timeout > $thurs_total_minutes_timein){
        $thurs_total_work = ($thurs_total_minutes_timeout - $thurs_total_minutes_timein) - 1;
    }else{
        $thurs_total_work = ($thurs_total_minutes_timein - $thurs_total_minutes_timeout) - 1;
    }      
}

// -----------------------SCHED FRIDAY START----------------------------//                                                        
if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){                                                                      
    $fri_timeIN = '00:00:00';
    $fri_timeout = '00:00:00';

    $FridaytimeIN = new DateTime($fri_timeIN);
    $FridaytimeOUT = new DateTime($fri_timeout);

    $timeDifference = $FridaytimeOUT->diff($FridaytimeIN);
    $fri_total_work = $timeDifference->format('%H:%I:%S');                                      
}else{
    $fri_timeIN = $row_Sched['fri_timein'];
    $fri_timeout = $row_Sched['fri_timeout'];

    // Create a DateTime object from the string
    $fri_timeIN_object = DateTime::createFromFormat('H:i', $fri_timeIN);
    $fri_timeIN_formatted = $fri_timeIN_object->format('H:i'); 
    list($fri_hours, $fri_minutes) = explode(':', $fri_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $fri_total_minutes_timein = $fri_hours + $fri_minutes;


    $fri_timeout_object = DateTime::createFromFormat('H:i', $fri_timeout);
    $fri_timeout_formatted = $fri_timeout_object->format('H:i'); 
    list($fri_hourss, $fri_minutess) = explode(':', $fri_timeout_formatted);

    // Convert hours and minutes to total minutes
    $fri_total_minutes_timeout = $fri_hourss + $fri_minutess;

    $fri_total_minutes_timein = intval($fri_total_minutes_timein);
    $fri_total_minutes_timeout = intval($fri_total_minutes_timeout);

        if($fri_total_minutes_timeout > $fri_total_minutes_timein){
        $fri_total_work = ($fri_total_minutes_timeout - $fri_total_minutes_timein) - 1;
    }else{
        $fri_total_work = ($fri_total_minutes_timein - $fri_total_minutes_timeout) - 1;
    }               
}

// -----------------------SCHED Saturday START----------------------------//
if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){                                                                                                          
    $sat_timeIN = '00:00:00';
    $sat_timeout = '00:00:00';

    $SaturdaytimeIN = new DateTime($sat_timeIN);
    $SaturdaytimeOUT = new DateTime($sat_timeout);

    $timeDifference = $SaturdaytimeOUT->diff($SaturdaytimeIN);
    $sat_total_work = $timeDifference->format('%H:%I:%S');
}else{                                          
    $sat_timeIN = $row_Sched['sat_timein'];
    $sat_timeout = $row_Sched['sat_timeout'];

    // Create a DateTime object from the string
    $sat_timeIN_object = DateTime::createFromFormat('H:i', $sat_timeIN);
    $sat_timeIN_formatted = $sat_timeIN_object->format('H:i'); 
    list($sat_hours, $sat_minutes) = explode(':', $sat_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $sat_total_minutes_timein = $sat_hours + $sat_minutes;


    $sat_timeout_object = DateTime::createFromFormat('H:i', $sat_timeout);
    $sat_timeout_formatted = $sat_timeout_object->format('H:i'); 
    list($sat_hourss, $sat_minutess) = explode(':', $sat_timeout_formatted);

    // Convert hours and minutes to total minutes
    $sat_total_minutes_timeout = $sat_hourss + $sat_minutess;

    $sat_total_minutes_timein = intval($sat_total_minutes_timein);
    $sat_total_minutes_timeout = intval($sat_total_minutes_timeout);
    
    if($sat_total_minutes_timeout > $sat_total_minutes_timein){
        $sat_total_work = ($sat_total_minutes_timeout - $sat_total_minutes_timein) - 1;
    }else{
        $sat_total_work = ($sat_total_minutes_timein - $sat_total_minutes_timeout) - 1;
    }        
}

 // -----------------------SCHED SUNDAY START----------------------------//
if ($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == '') {
    $sun_timeIN = '00:00:00';
    $sun_timeout = '00:00:00';

    $SundaytimeIN = new DateTime($sun_timeIN);
    $SundaytimeOUT = new DateTime($sun_timeout);

    $timeDifference = $SundaytimeOUT->diff($SundaytimeIN);
    $sun_total_work = $timeDifference->format('%H:%I:%S');
}else{
    $sun_timeIN = $row_Sched['sun_timein'];
    $sun_timeout = $row_Sched['sun_timeout'];

    // Create a DateTime object from the string
    $sun_timeIN_object = DateTime::createFromFormat('H:i', $sun_timeIN);
    $sun_timeIN_formatted = $sun_timeIN_object->format('H:i'); 
    list($sun_hours, $sun_minutes) = explode(':', $sun_timeIN_formatted);

    // Convert hours and minutes to total minutes
    $sun_total_minutes_timein = $sun_hours + $sun_minutes;

    $sun_timeout_object = DateTime::createFromFormat('H:i', $sun_timeout);
    $sun_timeout_formatted = $sun_timeout_object->format('H:i'); 
    list($sun_hourss, $sun_minutess) = explode(':', $sun_timeout_formatted);

    // Convert hours and minutes to total minutes
    $sun_total_minutes_timeout = $sun_hourss + $sun_minutess;

    $sun_total_minutes_timein = intval($sun_total_minutes_timein);
    $sun_total_minutes_timeout = intval($sun_total_minutes_timeout);


    if($sun_total_minutes_timeout > $sun_total_minutes_timein){
        $sun_total_work = ($sun_total_minutes_timeout - $sun_total_minutes_timein) - 1;
    }else{
        $sun_total_work = ($sun_total_minutes_timein - $sun_total_minutes_timeout) - 1;
    }                                                                  
}
?>