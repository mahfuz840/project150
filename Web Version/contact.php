<!DOCTYPE html>
<html lang="en">

    <!--Start of head area-->

    <head>
        <title>Run</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="form.css">

    </head>

    <!--End of head area-->

    <body>

        <!--Start of navbar area-->
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
     
            <!--End of navbar area-->

            <!--Start main area-->

            
<!-- Form Started -->
	<div class="container form-top" style="margin-top: 50px;margin-bottom: 50px">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
				<div class="panel panel-danger">
					<div class="panel-body">
						<form id="reused_form">
						 	<div class="form-group">
						 		<label><i class="fa fa-user" aria-hidden="true"></i> Name</label>
						 		<input type="text" name="name" class="form-control" placeholder="Enter Name" required>
						 	</div>
						 	<div class="form-group">
						 		<label><i class="fa fa-envelope" aria-hidden="true"></i> Email</label>
						 		<input type="email" name="email" class="form-control" placeholder="Enter Email" required>
						 	</div>
						 	<div class="form-group">
						 		<label><i class="fa fa-comment" aria-hidden="true"></i> Message</label>
						 		<textarea rows="3" name="message" class="form-control" placeholder="Type Your Message" required></textarea>
						 	</div>


						 	<div class="form-group">
						 		<button class="btn btn-raised btn-block btn-danger">Post →</button>
						 	</div>
						</form>
						<div id="error_message" style="width:100%; height:100%; display:none; ">
						<h4>Error</h4>
						Sorry there was an error sending your form.
						</div>
						<div id="success_message" style="width:100%; height:100%; display:none; ">
						<h2>Success! Your Message was Sent Successfully.</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- Form Ended -->
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