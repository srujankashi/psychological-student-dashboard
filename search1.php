<?php 
	include 'functions.php';
	
	if (!isset($_SESSION["user_id"]) && $_SESSION["user_id"] == '') {
    header('Location: index.php');
}
	if (isset($_POST) && !empty($_POST)) {
		$couseType = isset($_POST['course_type'])?$_POST['course_type']:'';
		$result = searchUniversity($_POST);
		if($result){
			$couseType = '';
			unset($_POST);
			unset($_REQUEST);
			header('Location: '.$_SERVER["PHP_SELF"], true, 303);
		}
	}
	//p($result);
?>

<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Psychological</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/freelancer.min.css" rel="stylesheet">
	<style>
		.hide{display:none !important;}
		.fontsize{font-size: 20px;font-weight: 600;}
		.blog-posts h2{font-size:25px;}
		.label-username{color: white;margin-left: 60%;}
	</style>
  </head>

  <body class="index" id="page-top">
	<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <a class="navbar-brand" href="#page-top">Psychological</a>
      <label class="label-username">WELCOME : <?php echo $_SESSION["username"] ;?></label>
      <a  href="logout.php">Logout</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fa fa-bars"></i>
      </button>
      <!-- <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="#portfolio">Portfolio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contact</a>
          </li>
        </ul>
      </div>-->
    </nav>

    <!-- Header -->
    <header class="masthead">
      <div class="container">
        <img class="img-fluid" src="img/profile.png" alt="">
        <div class="intro-text">
          <span class="name">Psychological</span>
          <hr class="star-light">
          <span class="skills">Find Best University for Your Course in Your City.</span>
        </div>
      </div>
    </header>

    <!-- Contact Section -->
    <section id="contact">
      <div class="container">
        <h2 class="text-center">Find University</h2>
        <hr class="star-primary">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
            <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
            <form id="form" method="post" enctype="multipart/form-data">
              <div class="control-group">
                <div class="form-group controls">
				  <?php echo stateDropdown('', 'onchange="getCityname(this)"','form-control'); ?>
                </div>
              </div>
			  <div class="control-group"   id="city_list_new">
                <div class="form-group controls">
                	<select class="form-control input-xlarge">
						<option>Select City</option>
					</select>
				</div>
              </div>
			  <div class="control-group" id="city_list">
                <div class="form-group controls">
					<div class="" id="city">

					</div>
				</div>   
			  </div>
			  <div class="control-group">
                <div class="form-group controls">
                	<select class="form-control" name="course_type" id="course_type">
						<option value="">Select Course Type</option>
						<option  value="0">Internal</option>
						<option  value="1">External</option>
						<option  value="2">Both</option>
					</select>
				</div>
              </div>
			  <div class="control-group">
                <div class="form-group controls">
                	<?php echo courseDropdown('','form-control');?>
				</div>
              </div>
			  <div class="control-group">
                <div class="form-group controls">
                	<select name="u_course_duration" class="form-control" id="u_course_duration">
						<option value="">Select Course Duration</option>
						<option value="6">6 Months</option>
						<option value="1">1 Year</option>
						<option value="2">2 Year</option>
						<option value="3">3 Year</option>
						<option value="4">4 Year</option>
					</select>
				</div>
              </div>
			  <br>
              <div id="success"></div>
              <div class="form-group">
                <!-- <button type="submit" class="btn btn-success btn-lg" id="submit">Search</button>-->
				<input id="submit" type="button" onClick="myFunction()" value="Search" class="btn btn-success btn-lg">
              </div>
            </form>
          </div>
        </div>
		<hr class="blog-post-sep">
		<div class="row">
			<div class="col-md-12 col-sm-12 blog-posts">
			    <div id="search_data">
					
				</div>
            </div>	  
		</div>
		
      </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
      <div class="footer-above">
        <div class="container">
          <div class="row">
            
          </div>
        </div>
      </div>
      <div class="footer-below">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              Copyright &copy; psychological 2017
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top d-lg-none">
      <a class="btn btn-primary page-scroll" href="#page-top">
        <i class="fa fa-chevron-up"></i>
      </a>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/freelancer.min.js"></script>
	<script>
		function getCityname(obj) {
			var state_id = obj.value;
			if (state_id != null) {
				$.ajax({
					type: 'POST',
					url: 'admin/controller.php?action=getCitynameFront&state_id=' + state_id,
					success: function (response) {
						var data = $.parseJSON(response);
						$('#city').html(data);
						$('#city_list').removeClass('hide');
						$('#city_list_new').addClass('hide');
					}
				});
			}
		}
		function myFunction(){
			var u_state_id = $("#u_state_id").val();
			var u_city_id = $("#u_city_id").val();
			var course_type = $("#course_type").val();
			var u_course_id = $("#u_course_id").val();
			var u_course_duration = $("#u_course_duration").val();
			var dataString = 'u_state_id='+ u_state_id + '&u_city_id='+ u_city_id + '&course_type='+ course_type + '&u_course_id='+ u_course_id+'&u_course_duration='+u_course_duration;
			if(u_state_id==''||u_city_id==''||course_type==''||u_course_id==''||u_course_duration==''){
				alert("Please Select All Fields");
			}else{
				$.ajax({
					type: 'post',
					url: 'search.php',
					data: dataString,
					success: function (response) {
						var data = $.parseJSON(response);
						$('#search_data').html(data);
					}
				});
			}
		}
		
	</script>
  </body>

</html>
