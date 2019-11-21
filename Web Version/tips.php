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

            
            <div class="container text-center" style="margin-top: 50px;margin-bottom: 50px;background-color: rgba(218, 229, 218,0.3);">
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,</p><br>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, 
                penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, 
                
                </p>
            </div>
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
    </body>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

</html>