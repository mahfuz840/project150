<!DOCTYPE html>
<html lang="en">

    <!--Start of head area-->

    <head>
        <title>Run</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

        <link rel="stylesheet" href="style.css">

    </head>

    <!--End of head area-->

    <body>

        <!--Start of navbar area-->
        <div>
        <div  >
            <div>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
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