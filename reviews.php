<!-- page to view reviews -->

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

                $sql = "SELECT  * FROM review ORDER BY Review_id ASC";


                $reviewresults = $conn->query($sql);

            ?>

            <div align = "center" class = "container" style = "padding-top: 15px; padding-bottom: 15px;">
                <form method = "post">
                    <input type="text" placeholder = "Search by Review Text" name = "search" size = "100">
                    <select name = "ratingFilter">
                            <option value = "0">None</option>
                            <option value = "1">1 Star</option>
                            <option value = "2">2 Stars</option>
                            <option value = "3">3 Stars</option>
                            <option value = "4">4 Stars</option>
                            <option value = "5">5 Stars</option>
                        </select>
                    <button class = "btn btn-dark" name = "submit">Search</button>
                </form>

            </div>

            <div align = "center" style = "font-size: 150%; font-family: Cambria;">
            <?php

            if(isset($_POST['submit'])) {

                $search = $_POST['search'];
                $filter = $_POST['ratingFilter'];

                    if($filter == 0)
                    {
                        if(empty($search)){
                            $newsql = $sql;
                        }
                        else {
                            $newsql = "SELECT  * FROM review WHERE Review_text LIKE '%$search%' ORDER BY Review_id ASC";
                        }
                        
                    }
                    else {
                        if(empty($search)){
                            $newsql = "SELECT  * FROM review WHERE rating = $filter ORDER BY Review_id ASC";
                        }
                        else {
                            $newsql = "SELECT  * FROM review WHERE Review_text LIKE '%$search%' AND rating = $filter ORDER BY Review_id ASC";
                        }
                        
                    }
                

                $reviewresults = $conn->query($newsql);

            } 

            $i = 0;
            
            echo '<table border = "0">';
            echo '<tr>';
                while($row = $reviewresults->fetch_assoc()) {
                    
                    if($i < 4) {
                        echo '<td>';
                            echo '<table border = "1" style = "text-align: center;">';

                                echo '<tr>';

                                $currentjobid = $row['job_id'];

                                echo '<td style="width: 300px;"> ' . $row['Rating'] . ' Stars </td>';
                                echo '</tr>';
                                echo '<tr>';

                                $sqlphoto = "SELECT photo_id, filename FROM photo AS photo1 WHERE photo1.job_id = $currentjobid ORDER BY photo_id ASC";
                                
                                $photoresults = $conn->query($sqlphoto);
                                
                                $count = 0;

                                while($row2 = $photoresults->fetch_assoc()){
                                    $count++;

                                    echo '<td><img src ="uploads/' . $row2['filename'] . '" class = "gallery" width="300" height="300"></td>';
                                }

                                if($count == 0) {
                                    echo '<td><img src ="No_Image_Available.jpg" class = "gallery" width="300" height="300"></td>';
                                }

                                echo '</tr>';
                                echo '<tr>';
                                echo '<td style="width: 300px; font-style: italic;">"' . $row['Review_text'] . '"</td>';
                                echo '</tr>';
                                echo '<tr>';

                                $sqljob = "SELECT Cost FROM job AS job1 WHERE job1.job_id = '$currentjobid'";

                                $jobresults = $conn->query($sqljob);

                                while($row3 = $jobresults->fetch_assoc()){

                                    echo '<td style="width: 300px"> $' . $row3['Cost'] . '</td>';

                                }

                                echo '</tr>';

                            echo '</table>'; 
                            echo '<br>'; 
                        echo '</td>';

                        $i++;
                    }
                        
                    else {

                        echo '<td>';
                        echo '<table border = "1" style = "text-align: center;">';

                            echo '<tr>';

                            $currentjobid = $row['job_id'];

                            echo '<td style="width: 300px;"> ' . $row['Rating'] . ' Stars </td>';
                            echo '</tr>';
                            echo '<tr>';

                            $sqlphoto = "SELECT photo_id, filename FROM photo AS photo1 WHERE photo1.job_id = $currentjobid ORDER BY photo_id ASC";
                            
                            $photoresults = $conn->query($sqlphoto);
                            
                            $count = 0;

                            while($row2 = $photoresults->fetch_assoc()){
                                $count++;

                                echo '<td><img src ="uploads/' . $row2['filename'] . '" class = "gallery" width="300" height="300"></td>';
                            }

                            if($count == 0) {
                                echo '<td><img src ="No_Image_Available.jpg" class = "gallery" width="300" height="300"></td>';
                            }

                            echo '</tr>';
                            echo '<tr>';
                            echo '<td style="width: 300px; font-style: italic;">"' . $row['Review_text'] . '"</td>';
                            echo '</tr>';
                            echo '<tr>';

                            $sqljob = "SELECT Cost FROM job AS job1 WHERE job1.job_id = '$currentjobid'";

                            $jobresults = $conn->query($sqljob);

                            while($row3 = $jobresults->fetch_assoc()){

                                echo '<td style="width: 300px"> $' . $row3['Cost'] . '</td>';

                            }

                            echo '</tr>';

                        echo '</table>'; 
                        echo '<br>'; 
                    echo '</td>';

                            echo '</tr>';

                            $i = 0;
                        }
                }
                echo '</tr>';
            echo '</table>';
                $reviewresults->close();
             ?>
        </div>

        <!-- loader  -->
<div class="loader-container">
    <img src="loader.gif" alt="">
</div>


<!-- custom js file link  -->
<script src="js.js"></script>

    </body>

</html>
