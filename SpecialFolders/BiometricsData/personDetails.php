<?php
    $emp_id = isset($_GET['empid']) ? $_GET['empid'] : '0';
    // $conn = mysqli_connect("localhost", "root", "", "hris_db");
    include '../../config.php';
    $query = "SELECT * FROM employee_tb WHERE empid = $emp_id";
    $result = $conn->query($query);

        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()){

                echo "<div class='form-group pl-4 pr-4'>";
                echo "<label for='username'> Firstname:</label><br>";
                echo "<input class='form-control' type='text' id='username' name='username' value=".$row['fname']." readonly>";
                echo "</div>";

                echo "<div class='form-group pl-4 pr-4'>";
                echo "<label for='lastname'> Lastname:</label><br>";
                echo "<input class='form-control ' type='text' name='lastname' value=".$row['lname']." readonly>";
                echo "</div>";

                echo "<div class='form-group pl-4 pr-4'>";
                echo "<label for='id'>Employee ID: </label><br>";
                echo "<input class='form-control' type='text' name='id' value=".$row['empid']." readonly>";
                echo "</div>";

                echo "<div class='form-group pl-4 pr-4'>";
                echo "<label for='contact' >Contact Number:</label><br>";
                echo "<input class='form-control' type='text' name='contact' value=".$row['contact']." readonly>";
                echo "</div>";


                echo "<div class='form-group pl-4 pr-4'>";
                echo "<label for='password'>Enter a Password: </label><br>";
                echo "<input class='form-control' type='password' name='password'  required>";
                echo "</div>";

                }
                echo "<div class='w-100 d-flex flex-direction-between flex-row justify-content-between'>";
                    echo "<div>";
                    echo "<p class='d-none'> hehe</p>";
                    echo "</div>";
                    echo "<input type='submit' name='add' value='Add' class='btn btn-primary mr-4'>";
                echo "</div>";
               
            }
            else{
                echo "<h3>No info available</h3>";
            }
?>
