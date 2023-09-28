<?php
// get_unit_types.php

// Include the configuration file and establish a database connection
include 'config.php';


// Get the employee ID from the URL parameter 'empid'
$empid = isset($_GET['empid']) ? intval($_GET['empid']) : 0;

// Construct the SQL query to fetch the unit types for the selected employee
$sql = "SELECT piece_rate_tb.unit_quantity, piece_rate_tb.unit_rate, piece_rate_tb.unit_type, piece_rate_tb.id AS piece_id FROM piece_rate_tb INNER JOIN employee_pakyawan_work_tb ON employee_pakyawan_work_tb.piece_rate_id = piece_rate_tb.id INNER JOIN employee_tb ON employee_pakyawan_work_tb.empid = employee_tb.empid WHERE employee_pakyawan_work_tb.empid = $empid;";

        


$result = mysqli_query($conn, $sql);

// Build the options for the "Unit Type" dropdown
$options = "";
while ($row = mysqli_fetch_assoc($result)) {
    // $options .= "<option value='" . $row['piece_id'] . "'>" . $row['unit_type'] . "</option>";
    @$subtotal = 0; 
    $unit_type = $row['unit_type'];
    $piece_id = $row['piece_id'];
    $unit_quantity = $row['unit_quantity']; 
    $unit_rate = $row['unit_rate'];

    $subtotal += $unit_rate / $unit_quantity;


    $options .= "
                <tr>
                    <td class='' style='font-weight: 400' id='box-cn'><div class='d-flex flex-row' id='boxes'><input class='checkbox-item' type='checkbox' id='box' name=''> <span class='unit_type'>".$unit_type."</span> <span class='subtotal d-none'>".$subtotal."</span></div</td>
                    <td class='' style='font-weight: 400'><input type='text' class='form-control quantity-item' style='width: 10em; margin-left: -3em' id='quantity' disabled> </td>
                    <td class='' style='font-weight: 400'><input type='text' class='form-control total-amount' style='width: 10em; margin-left: -2em' id='total' readonly></td>
                <tr>

                
            ";

        
}

// If no options are found, return a default option
if (empty($options)) {
    $options .= "No data";
}

// Return the options to the JavaScript code
// echo "<script> alert(".$row['id'].")</script>";
// echo "<option disabled selected>Select Unit Type </option>";
    echo "<div class='table-responsive mt-2 mb-2' style='height: 20em;' id='sample'> 
        <table class='table table-responsive table-borderless' style='overflow:hidden'> 
            <thead>
                    <th>Unit Type</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
            </thead>
            <tbody>
            ".$options. "
            
            </tbody>
        </table>
    </div>";

//     echo " <script>
//     console.log('hello');
//   </script>";

?>

