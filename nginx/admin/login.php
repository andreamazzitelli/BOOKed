<?php
   	session_start();
	include("../connect-db.php");
    
   	if($_SERVER["REQUEST_METHOD"] == "POST") {     
      	$myusername = mysqli_real_escape_string($mysqli,$_POST['username']);
      	$mypassword = mysqli_real_escape_string($mysqli,$_POST['password']);
		$salt = 'JMts#RhJf7YqF3tzCT!FUfX@9vdYJCjWq$C93D$tUDx%9N.8*A8Hj5mCFN$fE2w!4kk9v.f?2$RWjZfc#p2e4Qc3@V!qYA2';
		$mypwdcrypt = hash('sha512',$salt.$mypassword);
				
      	$sql = "SELECT id FROM Users WHERE username = '$myusername' and pass = '$mypwdcrypt'";
      	$result = mysqli_query($mysqli,$sql);
      	$row = mysqli_fetch_array($result,MYSQLI_ASSOC); 
      	$count = mysqli_num_rows($result);
      
      	if($count == 1) {
         	$_SESSION['login_user'] = $myusername;         
         	echo('<script type="text/javascript">window.location.href = "index.php";</script>');
      	} else {
         	$error = "Login errato... riprova";
      	}
   	}
    
   	$messages = array(  
        1 => 'Logout eseguito con successo.',  
        2 => 'Per accedere a questa pagina occorre essere loggati.'  
	);    
?>
<html>  
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>BookED ADMIN - Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <link href="../style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../font/flaticon.css" />
</head>
<body>

    <nav class="navbar navbar-expand-lg nav-on-scroll fixed-top position-fixed">
        <div class="container-fluid container">
            <h1><a class="navbar-brand" href="javascript:void();"><i class="flaticon-books"></i> BOOKed > Admin Login</a></h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html">Torna alla HomePage</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <!-- Lascio il div invece di solo a per farlo vedere meglio da mobile -->
                    <a href="../apidoc/" class="btn btn-light"><i class="flaticon-settings"></i> API SERVICES</a>
                </div>
            </div>
        </div>
    </nav>
    <section  class="container" id="prenota-page" style="margin-top: 15vh; padding-bottom: 5vh;">
        <div class="row mt-4">
            <div class="col-12">
                <?php 
	                if(isset($_GET['message']) && in_array($messages[$_GET['message']], $messages)){
    	                echo "<div class='alert alert-warning text-center'>".$messages[$_GET['message']]."</div>";
                    }
                ?>
            </div>
        </div>
        <form action="" method="post">
            <div class="row">
	            <h3>Admin Login</h3>
                <div class="col-12 col-md-6">        
                    <div class="input-group mb-3">
                        <span class="input-group-text">Username:</span>
                        <input type="text" id="username" name="username" class="form-control" required />
                    </div>
                </div>
                <div class="col-12 col-md-6">     
                    <div class="input-group mb-3">                        
                        <span class="input-group-text">Password:</span>
                        <input type="password" id="password" name="password" class="form-control" required />
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success full-width"><i class='flaticon-successo'></i> Esegui il login</button>
                </div>
            </div>
        </form>
        <?php 
            if($error != ''){
        ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-danger text-center"><?php echo $error ?></div>        
            </div>
        </div>
        <?php
            }
        ?>
    </section>


</body>
</html>
