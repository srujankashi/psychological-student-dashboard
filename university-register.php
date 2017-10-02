<?php
include 'functions.php';
if (isset($_POST["username"]) && $_POST["username"] != '' && isset($_POST["password"]) && $_POST["password"] != '') {
    $result = loginUser($_POST);
    setFlash($result);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:search1.php');
        exit;
    } else {
        header('location:user-login.php');
        exit;
    }
}

if (isset($_POST['email']) && $_POST['email'] != '') {
    $result = forgotPassword($_POST);
    setFlash($result);
    header('location:index.php');
    exit;
}

if (isset($_POST["usernamesignup"]) && $_POST["usernamesignup"] != '' && isset($_POST["emailsignup"]) && $_POST["emailsignup"] != '' && isset($_POST["passwordsignup"]) && $_POST["passwordsignup"] != '') {
    $result = universityRegister($_POST);
    setFlash($result);
    if (isset($result['status']) && $result['status'] == '0') {
        header('location:index.php');
        exit;
    } else {
        header('location:user-login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Login and Registration Form with HTML5 and CSS3</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
        <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
        <meta name="author" content="Codrops" />
        <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/freelancer.min.css" rel="stylesheet">
	
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style3.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
        <style>
		.hide{display:none !important;}
		.fontsize{font-size: 20px;font-weight: 600;}
		.blog-posts h2{font-size:25px;}
	</style>
    </head>
    <body class="index" id="page-top">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <a class="navbar-brand" href="#page-top">Psychological</a>
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
    			   <div class="container">
            <!-- Codrops top bar -->
            
            <section>				
                <div id="container_demo" >
                    <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  action="" autocomplete="on" method="post"> 
                                <h1> Sign up </h1> 
                               
                                <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Your FullName</label>
                                    <input id="usernamesignup" name="usernamesignup" required type="text" placeholder="myfullname" />
                                </p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                                    <input id="emailsignup" name="emailsignup" required type="email" placeholder="mysupermail@mail.com"/> 
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                    <input id="passwordsignup" name="passwordsignup" required type="password" placeholder="eg. X8df!90EO"/>
                                </p>
                                
                                
                                <p class="signin button"> 
									<input type="submit" value="Sign up"/> 
								</p>
                                <p class="change_link">  
									Already a member ?
									<a href="university-login.php" class="to_register"> Go and log in </a>
								</p>
                            </form>
                        </div>

                        
						
                    </div>
                </div>  
            </section>
        </div>

                
    </body>
</html>
    

   