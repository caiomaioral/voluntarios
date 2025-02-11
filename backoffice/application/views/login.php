<?php

$attributes = array('id' => 'Login-Form', 'name' => 'Login-Form');

echo form_open(base_url(), $attributes); 

?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <title><?php echo NAME_SITE; ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="shortcut icon" href="https://boladeneve.com/sites/default/files/bola-icons_0_0.png" type="image/png" />    
    
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<link href="<?php echo base_url(); ?>assets/css/login/login.css" rel="stylesheet" type="text/css" />

	<!-- Favicons -->
	<meta name="theme-color" content="#fff">

	https://bootsnipp.com/snippets/bxzmb

</head>
  
<body class="bg-light">
    <div class="container">
    
        <div class="py-5 text-center">
            <a href="<?php echo base_url(); ?>"><img class="d-block mx-auto mb-4" src="<?php echo base_url(); ?>assets/images/bola-de-neve-logo-preto.png" alt="" width="120" height="106"></a>
            <img class="d-block mx-auto mb-4" src="<?php echo base_url(); ?>assets/images/logo_siafsolutions_02.png" alt="" width="175" height="24">
   		</div>
	</div>

    <div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-info"><span>Esqueci minha senha</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php echo form_close(); ?>