<?php 
include 'config.php';

$pdfData = $_POST['pdfData'];
$Empid = $_POST['Empid'];
$Frequent = $_POST['Frequent'];
$Cutoffnumber = $_POST['Cutoffnumber'];
$WorkingDays = $_POST['WorkingDays'];
$CutoffId = $_POST['CutoffId'];

$decodedPdfData = base64_decode($pdfData);

date_default_timezone_set('Asia/Manila');
$currentDateTime = date('His');

$result_emp = mysqli_query($conn, "SELECT
                                        CONCAT(
                                            employee_tb.`fname`,
                                                ' ',
                                            employee_tb.`lname`
                                            ) AS `full_name`
                                            FROM 
                                            `employee_tb`
                                            WHERE `empid`=  '$Empid'");
 $row_emp= mysqli_fetch_assoc($result_emp);

 $pdfFilePath = 'Payslip PDF/' . $row_emp['full_name'] . $currentDateTime . "_" . $Cutoffnumber . '.pdf';
 $file = fopen($pdfFilePath, 'wb'); // Open the file in write mode
 if ($file) {
  fwrite($file, $decodedPdfData);
  fclose($file);
  echo "Done"; // Send a success response
  } else {
    echo "Error writing the PDF file.";
  }

if ($Frequent === 'Monthly'){

}else if($Frequent === 'Semi-Month'){
  $first_cutOFf = '1';
  $last_cutoff ='2';
}
else if($Frequent === 'Weekly'){
  $first_cutOFf = '1';
  $last_cutoff ='4';
}

    if ($Frequent === 'Monthly')
    {
        //for every cutoff loan deductions
        $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$Empid' AND `loan_status` != 'PAID' AND `status` = 'Approved'";
        $result = $conn->query($query);

        // Check if any rows are fetched
        if ($result->num_rows > 0) 
        {
        $loanArray = array(); // Array to store the dates

        // Loop through each row
        while($row = $result->fetch_assoc()) 
        {
        $loan_ID = $row["id"];
        $loan_payable = $row["payable_amount"];
        $loan_amortization = $row["amortization"];
        $loan_BAL = $row["col_BAL_amount"] ;

        $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
            
        } //end while

                    // Bind parameters and execute the statement for each loan
                    foreach ($loanArray as $loan_data) {
                        // Prepare the statement
                          $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                          $stmt = $conn->prepare($sql);
          
                          $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                          $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                          $stmt->execute();
          
                          if($diff_loan <= 0){
                            $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                            $stmt = $conn->prepare($sql);
          
                            $loan_stats = 'PAID';
                            $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                            $stmt->execute();
                          }  
                      }
                      $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                      $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
                      $stmt->execute();
          
                      echo 'Done';
    }else{
      $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
      $stmt->execute();
        echo 'Done';
    }
} else {
    if($Cutoffnumber === $first_cutOFf){
        $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$Empid' AND `loan_status` != 'PAID' AND `status` = 'Approved' AND (`applied_cutoff` = 'First Cutoff' OR `applied_cutoff` = 'Every Cutoff')";
        $result = $conn->query($query);

                  // Check if any rows are fetched
                  if ($result->num_rows > 0) {
                     $loanArray = array(); // Array to store the dates
            
                  // Loop through each row
                  while($row = $result->fetch_assoc()) 
                  {
                    $loan_ID = $row["id"];
                    $loan_payable = $row["payable_amount"];
                    $loan_amortization = $row["amortization"];
                    $loan_BAL = $row["col_BAL_amount"] ; 
                  
                    $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
                      
                  } //end while

                      foreach ($loanArray as $loan_data) {
                        // Prepare the statement
                          $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                          $stmt = $conn->prepare($sql);
        
                          $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                          $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                          $stmt->execute();
        
                          if($diff_loan <= 0){
                            $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                            $stmt = $conn->prepare($sql);
            
                            $loan_stats = 'PAID';
                            $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                            $stmt->execute();
                          }  
                      }

                        // Check if there's existing data in payslip_tb table
                        $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
                        $existingDataStmt = $conn->prepare($existingDataQuery);
                        $existingDataStmt->bind_param("sii", $Empid, $Cutoffnumber, $CutoffId);
                        $existingDataStmt->execute();
                        $existingDataResult = $existingDataStmt->get_result();

                        if ($existingDataResult->num_rows > 0) {
                            echo 'Data already exists.';

                        } else {
                            // Insert data into payslip_tb table
                            $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
                            $stmt->execute();

                            echo 'Done';
                        }
                  } else {
                    // Check if there's existing data in payslip_tb table
                    $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
                    $existingDataStmt = $conn->prepare($existingDataQuery);
                    $existingDataStmt->bind_param("sii", $Empid, $Cutoffnumber, $CutoffId);
                    $existingDataStmt->execute();
                    $existingDataResult = $existingDataStmt->get_result();

                    if ($existingDataResult->num_rows > 0) {
                        echo 'Data already exists.';

                    } else {
                        // Insert data into payslip_tb table
                        $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
                        $stmt->execute();

                        echo 'Done';
                    }
                  }
    } else if($Cutoffnumber === $first_cutOFf){
        $query = "SELECT * FROM payroll_loan_tb WHERE empid = $Empid AND loan_status != 'PAID' AND `status` = 'Approved' AND (`applied_cutoff` = 'Last Cutoff' OR `applied_cutoff` = 'Every Cutoff')";
        $result = $conn->query($query);
    
        // Check if any rows are fetched
        if ($result->num_rows > 0) 
        {
          $loanArray = array(); // Array to store the dates
  
        // Loop through each row
        while($row = $result->fetch_assoc()) 
        {
          $loan_ID = $row["id"];
          $loan_payable = $row["payable_amount"];
          $loan_amortization = $row["amortization"];
          $loan_BAL = $row["col_BAL_amount"] ; 
        
          $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
            
        } //end while

            foreach ($loanArray as $loan_data) {
              // Prepare the statement
                $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);

                $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                $stmt->execute();

                if($diff_loan <= 0){
                  $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                  $stmt = $conn->prepare($sql);
  
                  $loan_stats = 'PAID';
                  $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                  $stmt->execute();
                }  
            }
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $Empid, $Cutoffnumber, $CutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
                $stmt->execute();

                echo 'Done';
            }
        }else{
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $Empid, $Cutoffnumber, $CutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $Empid, $cutoffId, $WorkingDays);
                $stmt->execute();

                echo 'Done';
            }
        }
    }else if($Cutoffnumber === '2' || $Cutoffnumber === '3'){
        $query = "SELECT * FROM payroll_loan_tb WHERE empid = $Empid AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Every Cutoff'";
        $result = $conn->query($query);
    
        // Check if any rows are fetched
        if ($result->num_rows > 0) 
        {
        
              $loanArray = array(); // Array to store the dates
        
            // Loop through each row
            while($row = $result->fetch_assoc()) 
            {
    
              $loan_ID = $row["id"];
              $loan_payable = $row["payable_amount"];
              $loan_amortization = $row["amortization"];
              $loan_BAL = $row["col_BAL_amount"] ; 
            
              $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
                
            }
                foreach ($loanArray as $loan_data) {
                  // Prepare the statement
                    $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
    
                    $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                    $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                    $stmt->execute();
    
                    if($diff_loan <= 0){
                      $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                      $stmt = $conn->prepare($sql);
      
                      $loan_stats = 'PAID';
                      $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                      $stmt->execute();
                    }  
    
    
                }
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $Empid, $Cutoffnumber, $CutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
                $stmt->execute();

                echo 'Done';
            }
        }else{
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $Empid, $Cutoffnumber, $CutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $Empid, $CutoffId, $WorkingDays);
                $stmt->execute();

                echo 'Done';
            }
        }
    }
}

?>