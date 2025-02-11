<!DOCTYPE html>
<html lang="en">
<head>

    <title><?php echo NAME_SITE; ?></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="https://boladeneve.com/sites/default/files/bola-icons_0_0.png" type="image/png" />    
    
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet" type="text/css" />
    
    <link href="<?php echo base_url(); ?>assets/css/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css" />
	    
	<script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/login/login.js"></script>
    
</head>

<body>

<div id="content">

	<div id="templatemo_header_wrapper">
		<div id="templatemo_header">
            <div class="td_Title" style="padding-top:20px">
			
				<img src="<?php echo base_url(); ?>assets/images/wmb_logo.jpg" width="100" height="100" style="border: 1px #cecece solid">
				
			</div>
		</div>
	</div>
	
	<div id="templatemo_menu_wrapper">
		<div id="templatemo_menu"></div>
	</div>
	
	<div id="templatemo_content_wrapper">
		<div id="templatemo_content">
			<div class="content_box">
				<div id="Login">
					<div id="Caixa-Login">        
						
							<?php 
							
								$attributes = array('id' => 'FormX', 'name' => 'FormX');
							
								echo form_open(base_url() . 'login/enviar', $attributes); 
							
							?>
                        
                            <div class="cleaner" style="height: 20px"></div>
                            
                            <p><label>Login<br /><input id="user_login" name="login" type="text" size="20" maxlength="50" tabindex="10" value="<?php echo set_value('login'); ?>"></label></p>
                            <p><label>Senha<br /><input id="user_pass" name="pwd" type="password" size="20" maxlength="20" tabindex="20"></label></p>
                            
							<div class="g-recaptcha" data-sitekey="6LcNVwsqAAAAANke5h78JPoWG5UYmfORb4A3Y4qH" data-action="Login" data-callback="callback"></div>

							<p class="submit"><button id="wp-submit" name="wp-submit" type="submit" class="btn-login" disabled>Login</button></p>

                            <div class="msg_error">
                                <?php echo validation_errors(); ?>
                            </div>  
                            
                        <?php echo form_close(); ?>

					</div>
				</div>
			</div>

		</div>
		<div class="cleaner"></div>
	</div>
    
</div>
</body>
</html>