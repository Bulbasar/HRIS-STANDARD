<?php 

//     $server = "localhost";
//     $user = "root";
//     $pass ="";
//     $database = "hris_db";

//     $conn = mysqli_connect($server, $user, $pass, $database);

//     if(!$conn){
//         echo '<script type="text/javascript">';
//         echo 'alert("Connection Failed.");';
//         echo '</script>';
//         die;
// }


$servername = "node7894-slash.user.edgecloudph.com";
$username = "root";
$password = "GILfgt16112";
$dbname = "hris_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    echo '<script type="text/javascript">';
    echo 'alert("Connection Failed.");';
    echo '</script>';
    die;
}