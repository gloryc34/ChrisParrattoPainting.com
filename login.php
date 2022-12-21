<html>
<body>

<?php
$servername = "127.0.0.1";
$username = "root";
$password = "mysql";
$dbname = "chrisppaint";



$pword = $_POST["password"];
$email= $_POST["email"];


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT * FROM Account WHERE password = '$pword' and email = '$email'";

$result = $conn->query($sql);

if ($result->num_rows === 1) {
    session_start();
    $row = $result->fetch_assoc();
	unset($_SESSION['row']);

   
	if (!isset($_SESSION['row'])) {  
	
    $_SESSION['row'] = $row;        
    header("location: account.php");
	}

	
} else {

    echo "Error: Account not found" . $sql . "<br>" . $conn->error;
    header("location: signup.html");
}


$conn->close();



?>
</body>
</html>