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

                        }

                        else{


                            // If form submitted, insert values into the database.
                            if (isset($_POST['username'])){

                                $username = stripslashes($_REQUEST['username']); // removes backslashes
                                $username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
                                $password = stripslashes($_REQUEST['password']);
                                $password = mysqli_real_escape_string($con,$password);

                                //Checking is user existing in the database or not
                                $query = "SELECT * FROM `users` WHERE username='$username' and password='".md5($password)."'";
                                $result = mysqli_query($con,$query) or die(mysql_error());
                                $rows = mysqli_num_rows($result);
                                if($rows==1){
                                    $_SESSION['username'] = $username;

                                    header("Location: dashboard1.php");
                                    // Redirect user to index.php
                                }else{
                                    echo "<div><h6>Username/password is incorrect.</h6>Click here to <a href='index.php'>Login</a><a href='index.php' style='text-decoration:underline;color:red;'>  Forgot password?</a></div>";
                                }
                            }else{
                        ?>
                        <form action="" method="post" class="form-inline my-2 my-lg-0" style="font-size: 75%">
                            <div class="form-group" style="margin-right: 5px">
                                <label for="username">Username : </label>
                                <input type="username" class="form-control" id="username" placeholder="Enter Username" name="username" style="font-size:12px"  >
                            </div>
                            <div class="form-group" style="margin-right: 5px">
                                <label for="pwd">Password : </label>
                                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" style="font-size:12px">
                            </div>
                            <div class="checkbox" style="margin-right: 5px">
                                <label><input type="checkbox" name="remember"> Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-default" style="text-decoration: underline;margin-right: 5px;font-size:12px;">sign in</button>
                            <button type="submit" class="btn btn-default" style="font-size:12px;"><a href="registration.php">SignUp</a></button>
                        </form>
                        <?php } } ?>
                    </div>
                </nav>
            </div>
            <!--End of navbar area-->

            <!--Start main area-->

            <div style="margin-top: 0;margin-bottom: 0">
                <img src="images/9657a1b71bdd316.jpg" style="height:500px;width: 100%">
            </div>

            <!--End main area-->

            <!-- Start footer area-->
            <div class="jumbotron text-center"style="margin-bottom:0;margin-top: 0"  >
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
    <script>
        $(document).ready(function(){
            $("a").hover(function(){
                $("a").css();
            });
        });
    </script>
</html>