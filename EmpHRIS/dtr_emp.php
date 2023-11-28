<?php
    session_start(); 
include 'config.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>



<!-- skydash -->

<link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/dtr_emp.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dtr_empResponsives.css">
    <title>DTR CORRECTION - Employee</title>
</head>
<body>
    <header>
        <?php
            include 'header.php'
        ?>
</header>
<!----------------------------------------------Modal Start Here-------------------------------------------------------------->

<div class="modal fade" id="file_dtr_btn" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">DTR Correction Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="Data Controller/DTR Employee/dtr_conn.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">

            <input type="hidden" name="dtr_input_name" id="dtr_input_id">
            <div class="mb-3" style="display: none;">
                <label for="Select_emp" class="form-label">Employee Name</label>
                      <input type="text" class="form-control" name="name_emp" value="<?php echo $_SESSION['empid'];?>" id="empid" readonly>
              </div> <!--mb-3 end--->

              <div class="mb-3">
                  <label for="exampleInputDate" class="form-label">Date</label>
                  <input name="date_dtr" type="text" class="form-control" id="date_input" readonly>
              </div>

              <div class="mb-3">
                  <label for="exampleInputTime" class="form-label">Time</label>
                  <input name="time_dtr" type="time" class="form-control" id="time_input" required>
              </div>

              <div class="mb-3">
                  <label for="disabledSelect" class="form-label">Type</label>
                  <input type="text" name="select_type" id="type_select" class="form-control" required readonly>
              </div>

              <!-- <div class="mb-3">
                  <label for="floatingTextarea2" class="form-label">Reason</label>
                  <textarea name="text_reason" class="form-control" placeholder="Leave a reason here" id="floatingTextarea2" style="height: 100px" required></textarea>
              </div>
              
              <div class="input-group mb-3">
                      <input type="file" name="file_upload" class="form-control" id="inputGroupFile02">
                </div> -->
            </div> <!--Modal body div close tag-->
            <div class="modal-footer">
              <button type="submit" name="add_data" class="btn btn-primary">Add</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>


    </div>
  </div>
</div>
<!-------------------------------------------------End ng modal----------------------------------------------------------------->

<!----------------------------------------------View Modal Start Here-------------------------------------------------------------->

<div class="modal fade" id="view_file_dtr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Your DTR correction application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

        <div class="modal-body">

            <input type="hidden" name="dtr_view_name" id="dtr_view_id">
            <div class="mb-3" style="display: none;">
                <label for="Select_emp" class="form-label">Employee Name</label>
                      <input type="text" class="form-control" name="view_name_emp" value="<?php echo $_SESSION['empid'];?>" id="view_empid" readonly>
              </div>

              <div class="mb-3">
                  <label for="exampleInputDate" class="form-label">Date</label>
                  <input name="view_date_dtr" type="text" class="form-control" id="view_date_input" readonly>
              </div>

              <div class="mb-3">
                  <label for="exampleInputTime" class="form-label">Time</label>
                  <input name="view_time_dtr" type="text" class="form-control" id="view_time_input" readonly>
              </div>

              <div class="mb-3">
                  <label for="disabledSelect" class="form-label">Type</label>
                  <input type="text" name="view_select_type" id="view_type_select" class="form-control" readonly>
              </div>

              <div class="mb-3">
                  <label for="disabledSelect" class="form-label">Status</label>
                  <input type="text" class="form-control" name="view_status_name" id="view_status_id" readonly>
              </div>
            </div> <!--Modal body div close tag-->
    </div>
  </div>
</div>
<!-------------------------------------------------View End ng modal----------------------------------------------------------------->

<!------------------------------------------------Cancel MODAL------------------------------------------------------------------>
<div class="modal fade" id="cancelmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actions/DTR Employee/dtr_cancel.php" method="post">
      <div class="modal-body">
           <h4>You want to cancel your DTR Correction?</h4>
           <input type="hidden" name="dtr_ID" id="id_DTR">
           <input type="hidden" name="dtr_empid" id="empid_dtr">
      </div>
      <div class="modal-footer">
        <button type="submit" name="cancel_data" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------------------END OF Cancel MODAL--------------------------------------------------------->


<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download_dtr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Download PDF File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/DTR Employee/download_dtr.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_dl" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->


<!----------------------------------------------Class in overall design--------------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 16.7%; position: absolute; top: 0.5%;">
        <div class="content-wrapper mt-5" style="background-color: inherit">
            <div class="card" style="background-color: #f4f4f4;">
                <div class="card-body" style="width:1500px; height:780px; border-radius: 25px; box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);">
                    
<!----------------------------------------------End Class in overall design---------------------------------------------------->


<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 23px; font-weight: bold;">DTR Correction Application</h2>
                                </div>
                            </div>
<!----------------------------------End Class ng header including the button for modal-------------------------------------------->


<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="removeErrorFromURL()"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['error'])) {
            $err = $_GET['error'];
            echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            '.$err.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="removeErrorFromURL()"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->





                      <div class="table-responsive mt-5" id="table-responsiveness">
                        <table id="order-listing" class="table">
                          <thead>
                            <tr>
                                <th style="display: none;">ID</th>
                                <th style="display: none;">Employee ID</th>
                                <th>Date</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Remarks</th>
                                <th>Correction</th>
                                <th>Status</th>
                                <th>Action</th> 
                            </tr>
                          </thead>
                              <?php 
                                    $employeeid = $_SESSION['empid'];

                                    date_default_timezone_set('Asia/Manila');

                                    $currentMonthYear = date('Y-m');

                                    $query = "SELECT attendances.id,
                                    attendances.empid,
                                    attendances.date,
                                    attendances.time_in,
                                    attendances.time_out,
                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`
                                    FROM attendances
                                    INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
                                    WHERE attendances.empid = '$employeeid'
                                    AND DATE_FORMAT(attendances.date, '%Y-%m') = '$currentMonthYear'
                                    AND (attendances.time_in = '00:00:00' OR attendances.time_out = '00:00:00')";

                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {    

                                    $remarks_time_in = ($row['time_in'] === '00:00:00') ? '<span style="color:red;">NO TIME IN</span>' : 'N/A';
                                    $remarks_time_out = ($row['time_out'] === '00:00:00') ? '<span style="color:red;">NO TIME OUT</span>' : 'N/A';
                                    $remarks = ($row['time_in'] === '00:00:00') ? $remarks_time_in : $remarks_time_out;          

                                    $dtrQuery = "SELECT * FROM emp_dtr_tb WHERE `empid` = '$employeeid' AND `date` = '".$row['date']."'";
                                    $DtrRun = mysqli_query($conn, $dtrQuery);

                                    if(mysqli_num_rows($DtrRun) > 0){
                                      $dtrRow = mysqli_fetch_assoc($DtrRun);
                                      $dtrID = $dtrRow['id'];
                                      $dtrEmpid = $dtrRow['empid'];
                                      $dtrDate = $dtrRow['date'];
                                      $dtrTime = $dtrRow['time'];
                                      $dtrType = $dtrRow['type'];
                                      $dtrReason = $dtrRow['reason'];
                                      $dtrFile = $dtrRow['file_attach'];
                                      $dtrStatus = $dtrRow['status'];
                                   }  
                                    
                                  ?>
                                  <tr>
                                      <td style="display: none; font-weight: 400;"><?php echo $row['id']?></td>
                                      <td style="display: none; font-weight: 400;"><?php echo $employeeid?></td>
                                      <td style="font-weight: 400;"><?php echo $row['date']?></td>
                                      <td style="font-weight: 400; <?php if($row['time_in'] === '00:00:00') echo 'color:red;' ?>"><?php echo $row['time_in']?></td>
                                      <td style="font-weight: 400; <?php if($row['time_out'] === '00:00:00') echo 'color:red;' ?>"><?php echo $row['time_out']?></td>
                                      <td style="font-weight: 400; <?php if($remarks === '<span style="color:red;">NO TIME IN</span>' || $remarks === '<span style="color:red;">NO TIME OUT</span>') echo 'color:red;' ?>"><?php echo $remarks?></td>
                                      <td style="font-weight: 400;">
                                          <?php if (!empty($dtrStatus) && $dtrStatus === 'Cancelled'): ?>
                                              <button class="btn btn-outline-primary viewdtr" data-bs-toggle="modal" data-bs-target="#file_dtr_btn" title="Edit">File DTR</button>
                                          <?php elseif (!empty($dtrStatus) && in_array($dtrStatus, ['Approved', 'Rejected', 'Pending'])): ?>
                                              <button class="btn btn-outline-success viewfiledtr" data-bs-toggle="modal" data-bs-target="#view_file_dtr" title="View Details">View DTR</button>
                                          <?php else: ?>
                                              <button class="btn btn-outline-primary viewdtr" data-bs-toggle="modal" data-bs-target="#file_dtr_btn" title="Edit">File DTR</button>
                                          <?php endif; ?>
                                      </td>
                                      <td style="font-weight: 400;">
                                          <?php if (!empty($dtrStatus) && in_array($dtrStatus, ['Pending', 'Approved', 'Rejected', 'Cancelled'])): ?>
                                              <?php
                                              $color = '';
                                              switch ($dtrStatus) {
                                                  case 'Pending':
                                                      $color = 'orange';
                                                      break;
                                                  case 'Approved':
                                                      $color = 'green';
                                                      break;
                                                  case 'Rejected':
                                                      $color = 'red';
                                                      break;
                                                  case 'Cancelled':
                                                      $color = 'gray';
                                                      break;
                                                  }
                                              ?>
                                              <p style="color: <?php echo $color; ?>"><?php echo $dtrStatus; ?></p>
                                          <?php else: ?>
                                              <p>No request</p>
                                          <?php endif; ?>
                                      </td>
                                      <td style="font-weight: 400;">
                                            <?php if (!empty($dtrStatus) && !in_array($dtrStatus, ['Approved', 'Rejected', 'Cancelled'])): ?>
                                                <button class="btn btn-outline-danger cancelbtn" data-bs-toggle="modal" data-bs-target="#cancelmodal" type="button">Cancel</button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-danger cancelbtn" type="button" disabled style="display: none;">Cancel</button>
                                            <?php endif; ?>
                                        </td>
                                  </tr>
                              <?php
                                    }
                                ?>
                        </table>
                      </div>



                        
                </div><!----Close tag of Main Panel----->
            </div>
        </div>
    </div>

<!-------------------------------Script bawat pagclick ng file dtr---------------------------------------->        
<script>
    $(document).ready(function(){
        $('.viewdtr').on('click', function(){
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();
            console.log(data);
            $('#dtr_input_id').val(data[0]);
            $('#date_input').val(data[2]);

            // Check for the value 00:00:00 and handle it accordingly
            if (data[3] === '00:00:00' && data[4] !== '00:00:00') {
                $('#time_input').val('00:00:00');
            } else if (data[3] !== '00:00:00' && data[4] === '00:00:00') {
                $('#time_input').val('00:00:00');
            } else if(data[3] !== '00:00:00' && data[4] !== '00:00:00') {
                $('#time_input').val(data[3]);
            }
            
            if (data[3] === '00:00:00' && data[4] !== '00:00:00') {
                $('#type_select').val('IN');
            } else if (data[3] !== '00:00:00' && data[4] === '00:00:00') {
                $('#type_select').val('OUT');
            } else if(data[3] !== '00:00:00' && data[4] !== '00:00:00') {
                $('#type_select').val('IN');
            }
        });
    });
</script>

<!-----------------------------Script bawat pagclick ng file dtr------------------------------------->    

<!------------------------------------Script para sa whole view data ng request sa modal------------------------------------------------->
<script>
    $(document).ready(function() {
      $('.viewfiledtr').on('click', function() {
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();

        var empid = data[1];
        var date = data[2];

        $.ajax({
          url: 'fetch_dtr_data.php',
          method: 'POST',
          data: { empid: empid, date: date },
          success: function(response) {
            console.log(response); // Log the response
  var dtrData = JSON.parse(response);

            $('#dtr_view_id').val(dtrData.id);
            $('#view_empid').val(dtrData.empid);
            $('#view_date_input').val(dtrData.date);
            $('#view_time_input').val(dtrData.time);
            $('#view_type_select').val(dtrData.type);
            $('#view_status_id').val(dtrData.status);
            $('#view_floatingTextarea2').val(dtrData.reason);
            $('#view_inputGroupFile02').val(dtrData.file_attach);
          }
        });
      });
    });
</script>
<!---------------------------------End ng Script para sa whole view data ng request sa modal------------------------------------------>

<!-----------------------------Script sa pagremove ng message sa link------------------------------------>
<script>
    function removeErrorFromURL() {
        var url = new URL(window.location.href);
        url.searchParams.delete('error');
        url.searchParams.delete('msg');
        window.history.replaceState({}, document.title, url);
    }
</script>
<!-----------------------------Script sa pagremove ng message sa link------------------------------------>


<!----------------------------------FOR VIEW TRANSFER MODAL END------------------------------------------------>
<script>
    $(document).ready(function() {
      $('.cancelbtn').on('click', function() {
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();

        // Fetch additional data from emp_dtr_tb table
        var empid = data[1];
        var date = data[2];

        $.ajax({
          url: 'fetch_dtr_data.php',
          method: 'POST',
          data: { empid: empid, date: date },
          success: function(response) {
            var dtrData = JSON.parse(response);

            $('#id_DTR').val(dtrData.id);
            $('#empid_dtr').val(dtrData.empid);
          }
        });
      });
    });
</script>
<!----------------------------------FOR VIEW TRANSFER MODAL END------------------------------------------------>



<!---------------------------- Script para lumabas ang warning message na PDF File lang inaallow------------------------------------------>
<script>
  document.getElementById('inputfile').addEventListener('change', function(event) {
    var fileInput = event.target;
    var file = fileInput.files[0];
    if (file.type !== 'application/pdf') {
      alert('Please select a PDF file.');
      fileInput.value = ''; // Clear the file input field
    }
  });
</script>
<!--------------------End ng Script para lumabas ang Script para lumabas ang warning message na PDF File lang inaallow--------------------->

<!------------------------------------Script para sa download modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table').val(data[0]);
                   $('#name_table').val(data[2]);
               });
             });
</script>
<!---------------------------------End ng Script para download modal------------------------------------------>

<!-----------------------Script para sa automatic na pagdisapper ng alert message------------------------------->
<!-- <script>
    // Set a timer to remove the alert message after 2 seconds
    setTimeout(function(){
        document.getElementById("alert-message").remove();
    }, 2000);
</script> -->
<!---------------------End Script para sa automatic na pagdisapper ng alert message------------------------------>

<script> 
     $('.header-dropdown-btn').click(function(){
        $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
    });

//     $(document).ready(function() {
//     $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//     $('.dashboard-container').toggleClass('move-content');
  
//   });
// });
 $(document).ready(function() {
    var isHamburgerClicked = false;

    $('.navbar-toggler').click(function() {
    $('.nav-title').toggleClass('hide-title');
    // $('.dashboard-container').toggleClass('move-content');
    isHamburgerClicked = !isHamburgerClicked;

    if (isHamburgerClicked) {
      $('#dashboard-container').addClass('move-content');
    } else {
      $('#dashboard-container').removeClass('move-content');

      // Add class for transition
      $('#dashboard-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#dashboard-container').removeClass('move-content-transition');
      }, 800); // Adjust the timeout to match the transition duration
    }
  });
});
 

//     $(document).ready(function() {
//   $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//   });
// });


    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 390) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 500) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>





    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    
    <!--skydash-->
    <script src="skydash/vendor.bundle.base.js"></script>
    <script src="skydash/off-canvas.js"></script>
    <script src="skydash/hoverable-collapse.js"></script>
    <script src="skydash/template.js"></script>
    <script src="skydash/settings.js"></script>
    <script src="skydash/todolist.js"></script>
    <script src="main.js"></script>
    <script src="bootstrap js/data-table.js"></script>
    

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

</body>
</html>