<!DOCTYPE html>
<html lang="en">

    <!--Start of head area-->

    <head>
        <title>Run</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

        <link rel="stylesheet" href="style.css">
        <style>
            body {font-family: Arial, Helvetica, sans-serif;}
            * {box-sizing: border-box}

            /* Full-width input fields */
            input[type=text], input[type=password] {
                width: 100%;
                padding: 15px;
                margin: 5px 0 22px 0;
                display: inline-block;
                border: none;
                background: #f1f1f1;
            }

            input[type=text]:focus, input[type=password]:focus {
                background-color: #ddd;
                outline: none;
            }

            hr {
                border: 1px solid #f1f1f1;
                margin-bottom: 25px;
            }

            /* Set a style for all buttons */
            button {
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                cursor: pointer;
                width: 100%;
                opacity: 0.9;
            }

            button:hover {
                opacity:1;
            }

            /* Extra styles for the cancel button */
            .cancelbtn {
                padding: 14px 20px;
                background-color: #f44336;
            }

            /* Float cancel and signup buttons and add an equal width */
            .cancelbtn, .signupbtn {
                float: left;
                width: 50%;
            }

            /* Add padding to container elements */
            .container {
                padding: 16px;
            }

            /* Clear floats */
            .clearfix::after {
                content: "";
                clear: both;
                display: table;
            }

            /* Change styles for cancel button and signup button on extra small screens */
            @media screen and (max-width: 300px) {
                .cancelbtn, .signupbtn {
                    width: 100%;
                }
            }

        </style>
    </head>

    <!--End of head area-->

    <body>

        <!--Start of navbar area-->
        <div  >
            <div>
                <nav class="navbar navbar-expand-lg navbar-light " style="background-color:#ec6053;box-shadow:5px 5px 5px rgba(0,0,0,0.3);">
                    <a class="navbar-brand" href="#"><img src="images/hurry_improve_run_speed_avatar_business_human_man_people_user_have_a_seat-512.png" style="height: 30px"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="ranking.php">Ranking <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="tips.php">Tips <span class="sr-only">(current)</span></a>
                            </li>
                        </ul>

                        <?php
                        require('db.php');
                        session_start();

                        if(isset($_SESSION['username'])){
                            echo ' <a href="dashboard1.php" style="color: rgba(0,0,0,.9) ;margin-right:15px">My account</a>';
                            echo ' <a href="logout.php" style="color: rgba(0,0,0,.9)">Logout</a>';
                        }

                        ?>
                    </div>
                </nav>
            </div>
            <!--End of navbar area-->

            <!--Start main area-->
            <?php
            require('db.php');

            if(isset($_SESSION['username'])){
                $hi=$_SESSION['username'];
                if (isset($_REQUEST['username'])){
                    $username = stripslashes($_REQUEST['username']); // removes backslashes
                    $username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
                    $password = stripslashes($_REQUEST['password']);
                    $password = mysqli_real_escape_string($con,$password);
                    $name = stripslashes($_REQUEST['name']);
                    $name = mysqli_real_escape_string($con,$name);
                    $age = stripslashes($_REQUEST['age']);
                    $age = mysqli_real_escape_string($con,$age);
                    $weight= stripslashes($_REQUEST['weight']);
                    $weight = mysqli_real_escape_string($con,$weight);
                     $height= stripslashes($_REQUEST['height']);
                    $height = mysqli_real_escape_string($con,$height);
                    if($username!="" && $password!= null)
                        $query = "UPDATE `users` SET `username`= '$username',`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $name!="")
                        $query = "UPDATE `users` SET `username`= '$username',`name`='$name' ,`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $age!=null)
                        $query = "UPDATE `users` SET `username`= '$username',`age`=$age ,`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $weight!=null)
                        $query = "UPDATE `users` SET `username`= '$username',`weight`=$weight ,`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $name!="" && $age!=null && $weight!=null )
                        $query = "UPDATE `users` SET `username`= '$username',`name`='$name',`age`=$age,`weight`=$weight ,`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $name!="" && $age!=null)
                        $query = "UPDATE `users` SET `username`= '$username',`name`='$name',`age`=$age,`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $name!=""  && $weight!=null )
                        $query = "UPDATE `users` SET `username`= '$username',`name`='$name',`weight`=$weight ,`password`=MD5($password) where username=  '$hi'";

                    if($username!="" && $password!= null && $age!=null && $weight!=null )
                        $query = "UPDATE `users` SET `username`= '$username',`age`=$age,`weight`=$weight ,`password`=MD5($password) where username=  '$hi'";
                     if($username!="" && $password!= null && $name!="" && $age!=null && $weight!=null && $height!=null )
                        $query = "UPDATE `users` SET `username`= '$username',`age`=$age,`weight`=$weight ,`height`='$height',`password`=MD5('$password') where username=  '$hi'";


                    if($username!="" && $password!= null && $height!=null)
                        $query = "UPDATE `users` SET `username`= '$username',`height`=$height ,`password`=MD5($password) where username=  '$hi'";








                    $result = mysqli_query($con,$query);
                    if($result){

                        echo "<div class='form text-center' style='margin-top:200px;margin-bottom:200px;color:green;' ><h3>Your Dashboard Updated successfully.</h3><br/>Click here to <a href='index.php'>Login</a></div>";
                    }
                }else{
            ?>
            <div class="background">
                <div class="container" style="width:500px;">

                    <form method="post" action="" name="users" style="border:1px solid #ccc">
                        <div class="container" >
                            <h2 style="text-align:center">Update Profile</h2>
                            <p style="text-align:center">fill in this form to update your account.</p>
                            <hr>
                            <label for="username"><b>Username</b></label>
                            <input type="text" class="form-control" placeholder="Update Username" name="username" required>
                            <label for="name"><b>Name</b></label>
                            <input type="text" class="form-control" placeholder="Update Name" name="name">

                            <label for="password"><b>Password</b></label>
                            <input type="password" class="form-control" placeholder="Update Password" name="password" required>

                            <label for="age"><b>Age</b></label>
                            <input type="text" class="form-control"  placeholder="Update Age" name="age">
                            
                            <label for="weight"><b>Weight</b></label>
                            <input type="text" class="form-control"  placeholder="Update weight" name="weight">
                              <label for="height"><b>Height</b></label>
                            <input type="text" class="form-control"  placeholder="Update height" name="height">


                            <div class="clearfix">
                                <button type="button" class="cancelbtn" style="border-radius:18px"><a href="dashboard1.php" style="color:white;">Cancel</a></button>
                                <button type="submit" class="signupbtn" style="border-radius:18px">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php }}?>



            <!--End main area-->

            <!-- Start footer area-->
            <div class="jumbotron text-center"style="margin-bottom:0;margin-top: 0">
                <a href="about.php" style="color: black;margin-right: 35px">About us</a>
                <a href="contact.php" style="color: black;margin-right: 35px">Contact</a>
                <a href="policy.php" style="color: black;margin-right: 35px">Policy</a>

            </div>
            <!--End footer area-->
        </div>
    </body>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    

</html>