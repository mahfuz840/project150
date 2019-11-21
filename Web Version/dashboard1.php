<!DOCTYPE html>
<html lang="en">

    <!--Start of head area-->
<!DOCTYPE html>
<html lang="en">

    <!--Start of head area-->

    <head>
        <title>Run</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
     

        </style>
    </head>

    <!--End of head area-->

    <body>

        <!--Start of navbar area-->
    <div>
        <div  >
            <div >
                <nav class="navbar navbar-expand-lg navbar-light " style="background-color:#ec6053;box-shadow:5px 5px 5px rgba(0,0,0,0.3);">
                    <a class="navbar-brand" href="#"><img src="images/hurry_improve_run_speed_avatar_business_human_man_people_user_have_a_seat-512.png" style="height: 30px"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <div class="fox">
                            <li class="nav-item active">
                                <a class="nav-link" href="index.php" style="color:#f9f3f2;">Home <span class="sr-only">(current)</span></a>
                            </li>
                                </div>
                            <li class="nav-item active">
                                <a class="nav-link" href="ranking.php" style="color:#f9f3f2;">Ranking <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="tips.php" style="color:#f9f3f2;">Tips <span class="sr-only">(current)</span></a>
                            </li>
                        </ul>

                        <?php
                        require('db.php');
                        session_start();

                        if(isset($_SESSION['username'])){
                            echo ' <a  class="nav-link" href="dashboard1.php" style="color:#f9f3f2;margin-right:15px">My account</a>';
                            echo ' <a class="nav-link" href="logout.php" style="color:#f9f3f2;">Logout</a>';

                            $hi= $_SESSION['username'];
                            $query = "UPDATE `users` SET `height`=1000+1 where username=  '$hi'";
                            $result = mysqli_query($con,$query);

                        }?>

                        </div>
                    </nav>
                </div>
                <!--End of navbar area-->

                <!--Start main area-->

                <div class="container-fluid text-center "  style="margin-top: 50px;margin-bottom: 50px; width:450px;background-color: rgba(218, 229, 218,0.3);">
                    <h1 style="text-decoration:underline">DASHBOARD</h1>
                    <?php

                    $hi=$_SESSION['username'];
                    // Check connection
                    if ($con->connect_error) {
                        die("Connection failed: " . $con->connect_error);
                    } 
                    $sql = "SELECT username ,email ,name,age,weight,height,distance FROM users where username='$hi'";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        // output data of each row
                        echo "<table class = 'table table-striped table-bordered '  style='border:1px solid blue;font-family:monospace;'>" ;
                        while($row = $result->fetch_assoc()) {
                           
                            echo "<tr><td>"."<h5>"."Name"."</h5>"."</td><td>"."<h6>".$row["name"]."</h6>"."</td></tr>";
                            echo "<tr><td>"."<h5>"."Username"."</h5>"."</td><td>"."<h6>".$row["username"]."</h6>"."</td></tr>";
                            echo "<tr><td>"."<h5>"."Email"."</h5>"."</td><td>"."<h6>".$row["email"]."</h6>"."</td></tr>";
                            echo "<tr><td>"."<h5>"."Age"."</h5>"."</td><td>"."<h6>".$row["age"]."</h6>"."</td></tr>";
                            echo "<tr><td>"."<h5>"."Weight"."</h5>"."</td><td>"."<h6>".$row["weight"]."</h6>"."</td></tr>";
                            echo "<tr><td>"."<h5>"."Height"."</h5>"."</td><td>"."<h6>".$row["height"]."</h6>"."</td></tr>";
                            echo "<tr><td>"."<h5>"."Distance"."</h5>"."</td><td>"."<h6>".$row["distance"]."</h6>"."</td></tr>";
                       
                        }
                        echo "</table>";
                    }
                    else { echo "0 results"; }
                    $con->close();
                    ?>
                    <button type="submit" class="signupbtn" style="border-radius:4px;"><a href="update.php" style="color:black;text-transform:none;">Update Table</a></button>   
                </div>

                <!--End main area-->

                <!-- Start footer area-->
                <div class="jumbotron text-center"style="margin-bottom:0;margin-top: 0">
                    <a href="about.php" style="color: black;margin-right: 35px">About us</a>
                    <a href="contact.php" style="color: black;margin-right: 35px">Contact</a>
                    <a href="policy.php" style="color: black;margin-right: 35px">Policy</a>

                </div>
                <!--End footer area-->
            </div>
        </div>
    </body>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

</html>