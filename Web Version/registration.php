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
          /*  .background{
            background-image: url("images/Usain_Bolt_Ready_to_Run_Olympics_HD_Desktop_Wallpaper-HidefWall.Blogspot.Com_-1600x700.jpg");
            background-attachment: fixed;
          -webkit-filter: blur(5px);
         -moz-filter: blur(5px);
        -o-filter: blur(5px);
        -ms-filter: blur(5px);
         filter: blur(5px);

        
            }
            */
        </style>

    </head>

    <!--End of head area-->

    <body>
       
        <!--Start of navbar area-->
        <div>
            <div>
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
                    </div>
                </nav>
            </div>
            <!--End of navbar area-->

            <!--Start main area-->
             <?php
        require('db.php');
        // If form submitted, insert values into the database.
        if (isset($_REQUEST['username'])){
            $username = stripslashes($_REQUEST['username']); // removes backslashes
            $username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
            $email = stripslashes($_REQUEST['email']);
            $email = mysqli_real_escape_string($con,$email);
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($con,$password);

            $trn_date = date("Y-m-d H:i:s");
            $query = "INSERT into `users` (username, password, email, trn_date) VALUES ('$username', '".md5($password)."', '$email', '$trn_date')";
            $result = mysqli_query($con,$query);
            if($result){
                
            echo "<div class='form text-center' style='margin-top:200px;margin-bottom:200px;color:green;' ><h3>You are registered successfully.</h3><br/>Click here to <a href='index.php'>Login</a></div>";
            }
        }else{
        ?><div class="background">
            <div class="container" style="width:500px;">

            <form method="post" action="" name="registration" style="border:1px solid #ccc">
                <div class="container" >
                    <h2 style="text-align:center">Sign Up</h2>
                    <p style="text-align:center">Please fill in this form to create an account.</p>
                    <hr>
                    <label for="username"><b>Username</b></label>
                    <input type="text" class="form-control" placeholder="Enter Username" name="username" required>

                    <label for="email"><b>Email</b></label>
                    <input type="text" class="form-control" placeholder="Enter Email" name="email" required>

                    <label for="password"><b>Password</b></label>
                    <input type="password" class="form-control" placeholder="Enter Password" name="password" required>
                    
                    <label for="password"><b>Password</b></label>
                    <input type="password" class="form-control"  placeholder="Repeat Password" name="password" required>

                    <label>
                        <input type="checkbox" class="form-control" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
                    </label>

                    <p>By creating an account you agree to our <a href="terms.php" style="color:dodgerblue">Terms & Privacy</a>.</p>

                    <div class="clearfix">
                        <button type="button" class="cancelbtn" style="border-radius:18px">Cancel</button>
                        <button type="submit" class="signupbtn" style="border-radius:18px">Sign Up</button>
                    </div>
                </div>
            </form>
                </div>
            </div>
            <?php }?>


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