<!doctype html>
<html>
    
<?php

session_start();

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

    $ret = $_SESSION['row'];

    if(empty($ret['account_id'])) {
        header('Location: login.html');
    }

    $id = $ret['account_id'];

    $currentjobid = $_POST['updateJob'];
            
    //check for admin
    if($ret['admin'] == 1) {
        $newStatus = $_POST['cStatus'];

        if($newStatus == 5) {
            $sql = "DELETE FROM photo as photo1 WHERE photo1.job_id = '$currentjobid'; 
                    DELETE FROM job WHERE job_id = '$currentjobid'; ";
            
        }
        else {
            $sql = "UPDATE job SET status = '$newStatus' WHERE job_id = '$currentjobid'";
        }
        
    }
    else{
       $rating = $_POST['cRating'];
       $review = $_POST['review'];
       $description = $_POST['description'];

       $sql = "UPDATE job SET Description = '$description' WHERE job_id = '$currentjobid';
               INSERT INTO review (job_id, Rating, Review_text) VALUES ('$currentjobid', '$rating', '$review'); ";
    }

    if ($conn->multi_query($sql) === TRUE) {
        echo "Successfully updated!";
        header("location: account.php");
    } 
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    
    
    
    $conn->close();


    ?>

    

    </body>

</html>