<!-- page for user to manage their job -->
<!doctype html>
<html>
    
    <head>
        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="headers.css">
        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    
        <style>
            table,
            th,
            td {
                padding: 15px;
                border: 2;
                border-color: rgba(212, 188, 155, 0.781);
                border-style: dashed;
                border-collapse: collapse;
                background-color: white;
                }
                body {
                    background-color: #FAF9F6;
            }
        </style>

    </head>

    <body>


            <!-- Header -->
            <header class=" border-bottom">
                <div class="container-fluid">
                  <a href="about.php">
                        <img src = "company logo.png" class="auto-style1" style="height: 20%; width: 20%;" ></img>
                    </a>
                    <ul class="nav" style="float: right; padding-top: 55px;">
                        <li class="nav-item"><a href="login.html" class="nav-link link-dark px-2">Login</a></li>
                        <li class="nav-item"><a href="signup.html" class="nav-link link-dark px-2">Sign up</a></li>
                    </ul>
                </div>
            </header>

            <nav class="py-2 border-bottom" style="background-color:rgb(252, 223, 185) ;">
                <div class = "container d-flex flex-wrap justify-content-center" style="font-family: Cambria; font-size: larger;">
                    <ul class="nav">
                        <li><a href="about.php"  class="nav-link px-2 link-secondary">ABOUT</a></li>
                        <li><a href="reviews.php" class="nav-link px-2 link-dark">REVIEWS</a></li>
                        <li><a href="request.html" class="nav-link px-2 link-dark">REQUEST</a></li>
                        <li><a href="account.php" class="nav-link px-2 link-dark">ACCOUNT</a></li>
                    </ul>
                </div>
            </nav>
             <!-- Header -->

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

            $selectedjob = $_POST['address'];

            $sqljob = "SELECT * FROM job AS job1 WHERE job1.job_id = '$selectedjob'";

            $jobresult = $conn->query($sqljob);

        echo '<div align = "center" style = "font-size: 150%">';

        while($row = $jobresult->fetch_assoc()) {

            $current_status = $row['status'];

            $currentjobid = $row['job_id'];

            $jobaccountid = $row['account_id'];

            $jobstartdate = $row['StartDate'];

            $jobaddress = $row['Address'];

            $jobcost = $row['Cost'];

            $jobdescription = $row['Description'];

            switch($current_status) {
                case 1:
                    $current_status = 'Pending';
                    break;
                case 2:
                    $current_status = 'Started';
                    break;
                
                case 3:
                    $current_status = 'Awaiting Payment';
                    break;

                case 4:
                    $current_status = 'Completed';
                    break;
            }

            if($ret['admin'] == 1) {
            //admin view page
                if($row['status'] == 4){
                    //completed job page
                    echo '<form action = "updatejob.php" method = "post">';
                    echo '<table>';

                        echo '<tr>';
                        echo    '<td> <select name = "cStatus">';
                        echo                '<option value = "0"> ' . $current_status . '</option>';
                        echo                 '<option value = "1">Pending</option>';
                        echo                 '<option value = "2">Started</option>';
                        echo                 '<option value = "3">Awaiting Payment</option>';
                        echo                 '<option value = "4">Completed</option>';
                        echo                 '<option value = "5">Deny</option>';
                        echo            '</select></td>';
                        echo '</tr>';

                        echo '<tr>';

                        echo    '<td>';
                        
                        $sqlphoto = "SELECT photo_id, filename FROM photo AS photo1 WHERE photo1.job_id = $currentjobid ORDER BY photo_id ASC";
                        
                        $photoresults = $conn->query($sqlphoto);
                        
                        $count = 0;

                        while($row2 = $photoresults->fetch_assoc()){
                            $count++;

                            echo '<img src ="uploads/' . $row2['filename'] . '" class = "gallery" width="600" height="600">';
                        }

                        if($count == 0) {
                            echo '<img src ="No_Image_Available.jpg" class = "gallery" width="600" height="600">';
                        }
                        
                        echo '</td>';

                        echo    '<td>';
                        
                        $sqlaccount = "SELECT * FROM account AS account1 WHERE account1.account_id = $jobaccountid";

                        $accountresults = $conn->query($sqlaccount);

                        while($row3 = $accountresults->fetch_assoc()) {

                            echo '<b>Client First Name:</b> <br>';
                            echo $row3['first_name'];
                            echo '<br> <br>';
                            echo '<b>Client Last Name:</b> <br>';
                            echo $row3['last_name'];
                            echo '<br> <br>';
                            echo '<b>Client Email:</b> <br>';
                            echo $row3['email'];
                            echo '<br> <br>';
                            echo '<b>Client Phone Number:</b> <br>';
                            echo $row3['phone_number'];
                            echo '<br> <br>';
                            echo '<b>Job Start Date:</b> <br>';
                            echo $jobstartdate;
                            echo '<br> <br>';
                            echo '<b>Job Location:</b> <br>';
                            echo $jobaddress;
                        }
                        
                        echo '</td>';


                        echo '</tr>';
                        
                        echo '<tr>';
                        echo    '<td>';

                            echo '<b>Total Cost:</b> $' . $jobcost . '';
                        
                        echo        '</td>';
                       
                        
                        $sqlreview =  "SELECT * FROM review AS review1 WHERE review1.job_id = $currentjobid";

                        $reviewresults = $conn->query($sqlreview);

                        $counter = 0;

                        while($row4 = $reviewresults->fetch_assoc()){
                            
                            echo  '<td>';
                            echo '<b>Rating :</b> ' . $row4['Rating'] . ' Stars';
                            echo '</td>';

                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>';

                            echo '<b>Text Review :</b> <br>' . $row4['Review_text'] . '';
                            echo '</td>';
                            $counter++;
                        }

                        if($counter == 0) {
                            echo  '<td>';
                            echo '<b>Rating :</b> Not Yet Rated';
                            echo '</td>';

                            echo '<tr>';
                            echo '<td>';

                            echo '<b>Text Review :</b> <br> Not Yet Reviewed';
                            echo '</td>';
                        }
                        
                        

                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>';
                        
                        echo '<b>Job Description :</b> <br>' . $jobdescription . '';

                        echo    '</td>';

                        echo '</tr>';


                    echo '</table>';

                    echo '<button type = "submit" name = "updateJob" value = "'. $currentjobid .'">Update</button>';
                    echo '</form>';
                }
                else{
                    //incompleted job page
                    echo '<form action = "updatejob.php" method = "post">';
                    echo '<table>';

                        echo '<tr>';
                        echo    '<td> <select name = "cStatus">';
                        echo                '<option value = "0"> ' . $current_status . '</option>';
                        echo                 '<option value = "1">Pending</option>';
                        echo                 '<option value = "2">Started</option>';
                        echo                 '<option value = "3">Awaiting Payment</option>';
                        echo                 '<option value = "4">Completed</option>';
                        echo                 '<option value = "5">Deny</option>';
                        echo            '</select></td>';
                        echo '</tr>';

                        echo '<tr>';

                        echo    '<td>';

                        $sqlphoto = "SELECT photo_id, filename FROM photo AS photo1 WHERE photo1.job_id = $currentjobid ORDER BY photo_id ASC";
                        
                        $photoresults = $conn->query($sqlphoto);
                        
                        $count = 0;

                        while($row2 = $photoresults->fetch_assoc()){
                            $count++;

                            echo '<img src ="uploads/' . $row2['filename'] . '" class = "gallery" width="600" height="600">';
                        }

                        if($count == 0) {
                            echo '<img src ="No_Image_Available.jpg" class = "gallery" width="600" height="600">';
                        }
                        
                        echo '</td>';

                        echo    '<td>';
                        
                        $sqlaccount = "SELECT * FROM account AS account1 WHERE account1.account_id = $jobaccountid";

                        $accountresults = $conn->query($sqlaccount);

                        while($row3 = $accountresults->fetch_assoc()) {

                            echo '<b>Client First Name:</b> <br>';
                            echo $row3['first_name'];
                            echo '<br> <br>';
                            echo '<b>Client Last Name:</b> <br>';
                            echo $row3['last_name'];
                            echo '<br> <br>';
                            echo '<b>Client Email:</b> <br>';
                            echo $row3['email'];
                            echo '<br> <br>';
                            echo '<b>Client Phone Number:</b> <br>';
                            echo $row3['phone_number'];
                            echo '<br> <br>';
                            echo '<b>Job Start Date:</b> <br>';
                            echo $jobstartdate;
                            echo '<br> <br>';
                            echo '<b>Job Location:</b> <br>';
                            echo $jobaddress;
                        }
                        
                        echo '</td>';


                        echo '</tr>';
                        
                        echo '<tr>';
                        echo    '<td>';

                            echo '<b>Total Cost:</b> $' . $jobcost . '';
                        
                        echo        '</td>';
                       
                        
                        $sqlreview =  "SELECT * FROM review AS review1 WHERE review1.job_id = $currentjobid";

                        $reviewresults = $conn->query($sqlreview);

                        $counter = 0;

                        while($row4 = $reviewresults->fetch_assoc()){
                            
                            echo  '<td>';
                            echo '<b>Rating :</b> ' . $row4['Rating'] . ' Stars';
                            echo '</td>';

                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>';

                            echo '<b>Text Review :</b> <br>' . $row4['Review_text'] . '';
                            echo '</td>';
                            $counter++;
                        }

                        if($counter == 0) {
                            echo  '<td>';
                            echo '<b>Rating :</b> Not Yet Rated';
                            echo '</td>';

                            echo '<tr>';
                            echo '<td>';

                            echo '<b>Text Review :</b> <br> Not Yet Reviewed';
                            echo '</td>';
                        }
                        
                        

                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>';
                        
                        echo '<b>Job Description :</b> <br>' . $jobdescription . '';


                        echo    '</td>';

                        echo '</tr>';


                    echo '</table>';

                    echo '<button type = "submit" name = "updateJob" value = "'. $currentjobid .'">Update</button>';
                    echo '</form>';
                }
            }
            else{
            //user view page
                if($row['status'] == 4){
                    //completed job
                    echo '<form action = "updatejob.php" method = "post">';
                    echo '<table>';

                        echo '<tr>';
                        echo  '<td>';
                        echo  'Status : ' . $current_status . '';
                        echo '</td>';
                        echo '</tr>';

                        echo '<tr>';

                        echo    '<td>';

                        $sqlphoto = "SELECT photo_id, filename FROM photo AS photo1 WHERE photo1.job_id = $currentjobid ORDER BY photo_id ASC";
                        
                        $photoresults = $conn->query($sqlphoto);
                        
                        $count = 0;

                        while($row2 = $photoresults->fetch_assoc()){
                            $count++;

                            echo '<img src ="uploads/' . $row2['filename'] . '" class = "gallery" width="600" height="600">';
                        }

                        if($count == 0) {
                            echo '<img src ="No_Image_Available.jpg" class = "gallery" width="600" height="600">';
                        }
                        
                        echo '</td>';

                        echo    '<td>';
                        
                        $sqlaccount = "SELECT * FROM account AS account1 WHERE account1.account_id = $jobaccountid";

                        $accountresults = $conn->query($sqlaccount);

                        while($row3 = $accountresults->fetch_assoc()) {

                            echo '<b>Client First Name:</b> <br>';
                            echo $row3['first_name'];
                            echo '<br> <br>';
                            echo '<b>Client Last Name:</b> <br>';
                            echo $row3['last_name'];
                            echo '<br> <br>';
                            echo '<b>Client Email:</b> <br>';
                            echo $row3['email'];
                            echo '<br> <br>';
                            echo '<b>Client Phone Number:</b> <br>';
                            echo $row3['phone_number'];
                            echo '<br> <br>';
                            echo '<b>Job Start Date:</b> <br>';
                            echo $jobstartdate;
                            echo '<br> <br>';
                            echo '<b>Job Location:</b> <br>';
                            echo $jobaddress;
                        }
                        
                        echo '</td>';


                        echo '</tr>';
                        
                        echo '<tr>';
                        echo    '<td>';

                        echo '<b>Total Cost:</b> $' . $jobcost . '';
                        
                        echo        '</td>';
                        
                        
                        $sqlreview =  "SELECT * FROM review AS review1 WHERE review1.job_id = $currentjobid";

                        $reviewresults = $conn->query($sqlreview);

                        $counter2 = 0;

                        while($row4 = $reviewresults->fetch_assoc()){
                            echo    '<td>';
                            echo '<b>Rating :</b> ' . $row4['Rating'] . ' Stars';
                            echo '</td>';

                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>';

                            echo '<b>Text Review :</b> <br> <input type = "text" id ="review" name = "review" value = "' . $row4['Review_text'] . '" ></input>';
                            echo '</td>';
                            $counter2++;
                            
                        }

                        if($counter2 == 0) {

                                echo    '<td> <select  name = "cRating">';
                                echo                '<option value = "0">Rate Job</option>';
                                echo                 '<option value = "1">1 Star</option>';
                                echo                 '<option value = "2">2 Star</option>';
                                echo                 '<option value = "3">3 Star</option>';
                                echo                 '<option value = "4">4 Star</option>';
                                echo                 '<option value = "5">5 Star</option>';
                                echo            '</select></td>';
                                echo '</td>';

                                echo '</tr>';

                                echo '<tr>';
                                echo '<td>';

                                echo '<b>Text Review :</b> <br> <input type = "text" id ="review" name = "review" value = "Write a Review" ></input>';
                                echo '</td>';
                        }
                        
                       

                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>';
                        
                        echo '<b>Job Description :</b> <br> <input type = "text" id ="description" name = "description" value = " '. $jobdescription .' "></input>';

                        echo    '</td>';

                        echo '</tr>';


                    echo '</table>';

                    echo '<button type = "submit" name = "updateJob" value = "'. $currentjobid .'">Update</button>';
                    echo '</form>';
                }
                else{
                    echo '<form action = "updatejob.php" method = "post">';
                    echo '<table>';

                        echo '<tr>';
                        echo  '<td>';
                        echo  'Status : ' . $current_status . '';
                        echo '</td>';
                        echo '</tr>';

                        echo '<tr>';

                        echo    '<td>';

                        $sqlphoto = "SELECT photo_id, filename FROM photo AS photo1 WHERE photo1.job_id = $currentjobid ORDER BY photo_id ASC";
                        
                        $photoresults = $conn->query($sqlphoto);
                        
                        $count = 0;

                        while($row2 = $photoresults->fetch_assoc()){
                            $count++;

                            echo '<img src ="uploads/' . $row2['filename'] . '" class = "gallery" width="600" height="600">';
                        }

                        if($count == 0) {
                            echo '<img src ="No_Image_Available.jpg" class = "gallery" width="600" height="600">';
                        }
                        
                        echo '</td>';

                        echo    '<td>';
                        
                        $sqlaccount = "SELECT * FROM account AS account1 WHERE account1.account_id = $jobaccountid";

                        $accountresults = $conn->query($sqlaccount);

                        while($row3 = $accountresults->fetch_assoc()) {
                            
                            echo '<b>Client First Name:</b> <br>';
                            echo $row3['first_name'];
                            echo '<br> <br>';
                            echo '<b>Client Last Name:</b> <br>';
                            echo $row3['last_name'];
                            echo '<br> <br>';
                            echo '<b>Client Email:</b> <br>';
                            echo $row3['email'];
                            echo '<br> <br>';
                            echo '<b>Client Phone Number:</b> <br>';
                            echo $row3['phone_number'];
                            echo '<br> <br>';
                            echo '<b>Job Start Date:</b> <br>';
                            echo $jobstartdate;
                            echo '<br> <br>';
                            echo '<b>Job Location:</b> <br>';
                            echo $jobaddress;
                        }
                        
                        echo '</td>';


                        echo '</tr>';
                        
                        echo '<tr>';
                        echo    '<td>';

                        echo '<b>Total Cost:</b> $' . $jobcost . '';
                        
                        echo '</td>';

                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>';
                        
                        echo '<b>Job Description :</b> <br> <input type = "text" id ="description" name = "description" value = " '. $jobdescription .' "></input>';

                        echo    '</td>';

                        echo '</tr>';


                    echo '</table>';

                    echo '<button type = "submit" name = "updateJob" value = "'. $currentjobid .'">Update</button>';
                    echo '</form>';
                }

            }
            
        }

    echo '</div>';    
    ?>
    </body>

</html>
