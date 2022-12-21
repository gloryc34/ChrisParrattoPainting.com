<html>
<body>

<?php

session_start();

$ret = $_SESSION['row'];

$servername = "127.0.0.1";
$username = "root";
$password = "mysql";
$dbname = "chrisppaint";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    
} 

if(empty($ret['account_id'])) {
    header('Location: login.html');
}

$id = $ret['account_id'];
$date = date('Y-m-d', strtotime($_POST["start-date"]));
$address = $_POST["address"];
$description = $_POST["job-d"];
$cost = $_POST["cost"];
$img = $_FILES['image'];
$imgsize = $img["size"];

$sql = "INSERT INTO job (account_id, status, StartDate, Address, Description, Cost) VALUES ('$id', '1', '$date', '$address', '$description', '$cost')";

if ($conn->query($sql) === TRUE) {
    echo "Requested successfully!";
    $jobid = $conn->insert_id;
} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

if($imgsize > 0) {
        
        $img = $_FILES['image'];
        $imgname = $img['name'];
        $imgtemp = $img["tmp_name"];
        $imgsize = $img["size"];
        $imgerror = $img["error"];
        $imgtype = $img["type"];

        $folder = "uploads/" . $imgname;

        $sqlphoto = " INSERT INTO photo (filename, job_id) VALUES ('$imgname', '$jobid') ";

        if ($conn->query($sqlphoto) === TRUE) {
            if (move_uploaded_file($imgtemp, $folder)) {

                echo "Photo uploaded successfully!";
                header("location: account.php");

            } else {
                echo "Error: " . $sqlphoto . "<br>" . $conn->error;
            }
        }
}
else {
    
    header("location: account.php");
}    

$conn->close();

?>
</body>
</html> 