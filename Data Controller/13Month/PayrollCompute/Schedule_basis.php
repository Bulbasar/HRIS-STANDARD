<?php
// -----------------------SCHED MONDAY START----------------------------//
if($row_Scheds['mon_timein'] == NULL || $row_Scheds['mon_timein'] == ''){                         
    $MON_timeINS = '00:00:00';
    $MON_timeOUTS = '00:00:00'; 

    // convert ang time na nakastring sa DateTime
    $MondaytimeINS = new DateTime($MON_timeINS);
    $MondaytimeOUTS = new DateTime($MON_timeOUTS);
    
    $timeDifferences = $MondaytimeOUTS->diff($MondaytimeINS);
    $MOn_total_works = $timeDifferences->format('%H:%I:%S');

    // // naconvert ko ang value ng $totalSeconds from time to integer
    // $totalSeconds = $timeDifference->s + ($timeDifference->i * 60) + ($timeDifference->h * 3600);
    // $MOn_total_work = (int)$totalSeconds;
}else{
    $MON_timeINS = $row_Scheds['mon_timein'];
    $MON_timeOUTS = $row_Scheds['mon_timeout'];

    // Create a DateTime object from the string
    $mon_timeIN_objects = DateTime::createFromFormat('H:i', $MON_timeINS);
    $mon_timeIN_formatteds = $mon_timeIN_objects->format('H:i'); 
    list($monhours, $monminutes) = explode(':', $mon_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $mon_total_minutes_timeins = $monhours + $monminutes;

    $mon_timeout_objects = DateTime::createFromFormat('H:i', $MON_timeOUTS);
    $mon_timeout_formatteds = $mon_timeout_objects->format('H:i'); 
    list($monhourss, $monminutess) = explode(':', $mon_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $mon_total_minutes_timeouts = $monhourss + $monminutess;

    $mon_total_minutes_timeins = intval($mon_total_minutes_timeins);
    $mon_total_minutes_timeouts = intval($mon_total_minutes_timeouts);
       
    if($mon_total_minutes_timeouts > $mon_total_minutes_timeins){
        $MOn_total_works = ($mon_total_minutes_timeouts - $mon_total_minutes_timeins) - 1;
    }else{
        $MOn_total_works = ($mon_total_minutes_timeins - $mon_total_minutes_timeouts) - 1;
    }
}

// -----------------------SCHED TUESDAY START----------------------------//
if($row_Scheds['tues_timein'] == NULL || $row_Scheds['tues_timein'] == ''){
    $tue_timeINS = '00:00:00';
    $tue_timeouts = '00:00:00';

    $TuesdaytimeINS = new DateTime($tue_timeINS);
    $TuesdaytimeOUTS = new DateTime($tue_timeouts);
    
    $timeDifferences = $TuesdaytimeOUTS->diff($TuesdaytimeINS);
    $Tue_total_works = $timeDifferences->format('%H:%I:%S');
}else{
    $tue_timeINS = $row_Scheds['tues_timein'];
    $tue_timeouts = $row_Scheds['tues_timeout'];
    
    $tue_timeIN_objects = DateTime::createFromFormat('H:i', $tue_timeINS);
    $tue_timeIN_formatteds = $tue_timeIN_objects->format('H:i'); 
    list($tuehours, $tueminutes) = explode(':', $tue_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $tue_total_minutes_timeins = $tuehours + $tueminutes;

    $tue_timeout_objects = DateTime::createFromFormat('H:i', $tue_timeouts);
    $tue_timeout_formatteds = $tue_timeout_objects->format('H:i'); 
    list($tuehourss, $tueminutess) = explode(':', $tue_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $tue_total_minutes_timeouts = $tuehourss + $tueminutess;

    $tue_total_minutes_timeins = intval($tue_total_minutes_timeins);
    $tue_total_minutes_timeouts = intval($tue_total_minutes_timeouts);

    if($tue_total_minutes_timeouts > $tue_total_minutes_timeins){
        $Tue_total_works = ($tue_total_minutes_timeouts - $tue_total_minutes_timeins) - 1;
    }else{
        $Tue_total_works = ($tue_total_minutes_timeins - $tue_total_minutes_timeouts) - 1;
    }
}

// -----------------------SCHED WEDNESDAY START----------------------------//
if($row_Scheds['wed_timein'] == NULL || $row_Scheds['wed_timein'] == ''){
    $wed_timeINS = '00:00:00';
    $wed_timeouts = '00:00:00';

    $WednesdaytimeINS = new DateTime($wed_timeINS);
    $WednesdaytimeOUTS = new DateTime($wed_timeouts);

    $timeDifferences = $WednesdaytimeOUTS->diff($WednesdaytimeINS);
    $wed_total_works = $timeDifferences->format('%H:%I:%S');
}else{
    $wed_timeINS = $row_Scheds['wed_timein'];
    $wed_timeouts = $row_Scheds['wed_timeout'];

    $weds_timeIN_objects = DateTime::createFromFormat('H:i', $wed_timeINS);
    $weds_timeIN_formatteds = $weds_timeIN_objects->format('H:i'); 
    list($wedshours, $wedsminutes) = explode(':', $weds_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $weds_total_minutes_timeins = $wedshours + $wedsminutes;

    $weds_timeout_objects = DateTime::createFromFormat('H:i', $wed_timeouts);
    $weds_timeout_formatteds = $weds_timeout_objects->format('H:i'); 
    list($wedshourss, $wedsminutess) = explode(':', $weds_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $weds_total_minutes_timeouts = $wedshourss + $wedsminutess;

    $weds_total_minutes_timeins = intval($weds_total_minutes_timeins);
    $weds_total_minutes_timeouts = intval($weds_total_minutes_timeouts);

    if($weds_total_minutes_timeouts > $weds_total_minutes_timeins){
        $wed_total_works = ($weds_total_minutes_timeouts - $weds_total_minutes_timeins) - 1;
    }else{
        $wed_total_works = ($weds_total_minutes_timeins - $weds_total_minutes_timeouts) - 1;
    }
}

// -----------------------SCHED THURSDAY START----------------------------//
if($row_Scheds['thurs_timein'] == NULL || $row_Scheds['thurs_timein'] == ''){                                                                       
    $thurs_timeINS = '00:00:00';
    $thurs_timeouts = '00:00:00';

    $ThursdaytimeINS = new DateTime($thurs_timeINS);
    $ThursdaytimeOUTS = new DateTime($thurs_timeouts);

    $timeDifferences = $ThursdaytimeOUTS->diff($ThursdaytimeINS);
    $thurs_total_works = $timeDifferences->format('%H:%I:%S');                                     
}else{
    $thurs_timeINS = $row_Scheds['thurs_timein'];
    $thurs_timeouts = $row_Scheds['thurs_timeout'];
    
    // Create a DateTime object from the string
    $thurs_timeIN_objects = DateTime::createFromFormat('H:i', $thurs_timeINS);
    $thurs_timeIN_formatteds = $thurs_timeIN_objects->format('H:i'); 
    list($thurshours, $thursminutes) = explode(':', $thurs_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $thurs_total_minutes_timeins = $thurshours + $thursminutes;


    $thurs_timeout_objects = DateTime::createFromFormat('H:i', $thurs_timeouts);
    $thurs_timeout_formatteds = $thurs_timeout_objects->format('H:i'); 
    list($thurshourss, $thursminutess) = explode(':', $thurs_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $thurs_total_minutes_timeouts = $thurshourss + $thursminutess;

    $thurs_total_minutes_timeins = intval($thurs_total_minutes_timeins);
    $thurs_total_minutes_timeouts = intval($thurs_total_minutes_timeouts);

    if($thurs_total_minutes_timeouts > $thurs_total_minutes_timeins){
        $thurs_total_works = ($thurs_total_minutes_timeouts - $thurs_total_minutes_timeins) - 1;
    }else{
        $thurs_total_works = ($thurs_total_minutes_timeins - $thurs_total_minutes_timeouts) - 1;
    }      
}

// -----------------------SCHED FRIDAY START----------------------------//                                                        
if($row_Scheds['fri_timein'] == NULL || $row_Scheds['fri_timein'] == ''){                                                                      
    $fri_timeINS = '00:00:00';
    $fri_timeouts = '00:00:00';

    $FridaytimeINS = new DateTime($fri_timeINS);
    $FridaytimeOUTS = new DateTime($fri_timeouts);

    $timeDifferences = $FridaytimeOUTS->diff($FridaytimeINS);
    $fri_total_works = $timeDifferences->format('%H:%I:%S');                                      
}else{
    $fri_timeINS = $row_Scheds['fri_timein'];
    $fri_timeouts = $row_Scheds['fri_timeout'];

    // Create a DateTime object from the string
    $fri_timeIN_objects = DateTime::createFromFormat('H:i', $fri_timeINS);
    $fri_timeIN_formatteds = $fri_timeIN_objects->format('H:i'); 
    list($frihours, $friminutes) = explode(':', $fri_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $fri_total_minutes_timeins = $frihours + $friminutes;


    $fri_timeout_objects = DateTime::createFromFormat('H:i', $fri_timeouts);
    $fri_timeout_formatteds = $fri_timeout_objects->format('H:i'); 
    list($frihourss, $friminutess) = explode(':', $fri_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $fri_total_minutes_timeouts = $frihourss + $friminutess;

    $fri_total_minutes_timeins = intval($fri_total_minutes_timeins);
    $fri_total_minutes_timeouts = intval($fri_total_minutes_timeouts);

        if($fri_total_minutes_timeouts > $fri_total_minutes_timeins){
        $fri_total_works = ($fri_total_minutes_timeouts - $fri_total_minutes_timeins) - 1;
    }else{
        $fri_total_works = ($fri_total_minutes_timeins - $fri_total_minutes_timeouts) - 1;
    }               
}

// -----------------------SCHED Saturday START----------------------------//
if($row_Scheds['sat_timein'] == NULL || $row_Scheds['sat_timein'] == ''){                                                                                                          
    $sat_timeINS = '00:00:00';
    $sat_timeouts = '00:00:00';

    $SaturdaytimeINS = new DateTime($sat_timeINS);
    $SaturdaytimeOUTS = new DateTime($sat_timeouts);

    $timeDifferences = $SaturdaytimeOUTS->diff($SaturdaytimeINS);
    $sat_total_works = $timeDifferences->format('%H:%I:%S');
}else{                                          
    $sat_timeINS = $row_Scheds['sat_timein'];
    $sat_timeouts = $row_Scheds['sat_timeout'];

    // Create a DateTime object from the string
    $sat_timeIN_objects = DateTime::createFromFormat('H:i', $sat_timeINS);
    $sat_timeIN_formatteds = $sat_timeIN_objects->format('H:i'); 
    list($sathours, $satminutes) = explode(':', $sat_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $sat_total_minutes_timeins = $sathours + $satminutes;


    $sat_timeout_objects = DateTime::createFromFormat('H:i', $sat_timeouts);
    $sat_timeout_formatteds = $sat_timeout_objects->format('H:i'); 
    list($sathourss, $satminutess) = explode(':', $sat_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $sat_total_minutes_timeouts = $sathourss + $satminutess;

    $sat_total_minutes_timeins = intval($sat_total_minutes_timeins);
    $sat_total_minutes_timeouts = intval($sat_total_minutes_timeouts);
    
    if($sat_total_minutes_timeouts > $sat_total_minutes_timeins){
        $sat_total_works = ($sat_total_minutes_timeouts - $sat_total_minutes_timeins) - 1;
    }else{
        $sat_total_works = ($sat_total_minutes_timeins - $sat_total_minutes_timeouts) - 1;
    }        
}

 // -----------------------SCHED SUNDAY START----------------------------//
if ($row_Scheds['sun_timein'] == NULL || $row_Scheds['sun_timein'] == '') {
    $sun_timeINS = '00:00:00';
    $sun_timeouts = '00:00:00';

    $SundaytimeINS = new DateTime($sun_timeINS);
    $SundaytimeOUTS = new DateTime($sun_timeouts);

    $timeDifferences = $SundaytimeOUTS->diff($SundaytimeINS);
    $sun_total_works = $timeDifferences->format('%H:%I:%S');
}else{
    $sun_timeINS = $row_Scheds['sun_timein'];
    $sun_timeouts = $row_Scheds['sun_timeout'];

    // Create a DateTime object from the string
    $sun_timeIN_objects = DateTime::createFromFormat('H:i', $sun_timeINS);
    $sun_timeIN_formatteds = $sun_timeIN_objects->format('H:i'); 
    list($sunhours, $sunminutes) = explode(':', $sun_timeIN_formatteds);

    // Convert hours and minutes to total minutes
    $sun_total_minutes_timeins = $sunhours + $sunminutes;

    $sun_timeout_objects = DateTime::createFromFormat('H:i', $sun_timeouts);
    $sun_timeout_formatteds = $sun_timeout_objects->format('H:i'); 
    list($sunhourss, $sunminutess) = explode(':', $sun_timeout_formatteds);

    // Convert hours and minutes to total minutes
    $sun_total_minutes_timeouts = $sunhourss + $sunminutess;

    $sun_total_minutes_timeins = intval($sun_total_minutes_timeins);
    $sun_total_minutes_timeouts = intval($sun_total_minutes_timeouts);


    if($sun_total_minutes_timeouts > $sun_total_minutes_timeins){
        $sun_total_works = ($sun_total_minutes_timeouts - $sun_total_minutes_timeins) - 1;
    }else{
        $sun_total_works = ($sun_total_minutes_timeins - $sun_total_minutes_timeouts) - 1;
    }                                                                  
}

?>