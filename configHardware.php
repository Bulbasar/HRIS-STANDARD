<!-- <script>
        function autoReload() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Replace the content of a specific element with the response from hardwareController.php
                    // document.getElementById("hardware-content").innerHTML = this.responseText;
                    console.log("hardwareController.php content reloaded."); // Add a log message
                }
            };
            xhttp.open("GET", "Data Controller/Attendance/hardwareController.php", true);
            xhttp.send();
        }

        // Refresh every 15 seconds (5000 milliseconds)
        setInterval(autoReload, 25000);
    </script>


<script>
        function autoReload() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Replace the content of a specific element with the response from hardwareController.php
                    // document.getElementById("hardware-db").innerHTML = this.responseText;
                    console.log("hardware to db content reloaded."); // Add a log message
                }
            };
            xhttp.open("GET", "SpecialFolders/BiometricsData/AddAttendanceToDb.php", true);
            xhttp.send();
        }

        // Refresh every 15 seconds (5000 milliseconds)
        setInterval(autoReload, 15000);
    </script> -->