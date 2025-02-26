<!DOCTYPE html>
<html lang="pt">
<head>
	
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <?php echo $CssProjects; ?>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/scripts/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>

    <?php echo $AddJs; ?>
    
</head>

<body>
    
    <?php $attributes = array('id' => 'FormX', 'name' => 'FormX', 'class' => 'form'); ?>

    <div id="login">
        <div class="py-5 text-center">
            <a href="<?php echo base_url(); ?>"><img class="d-block mx-auto mb-4" src="<?php echo base_url(); ?>assets/images/bola-de-neve-logo-preto.png" alt="" width="120" height="106"></a>
            <img class="d-block mx-auto mb-4" src="<?php echo base_url(); ?>assets/images/logo_siafsolutions_02.png" alt="" width="175" height="24">
   		</div>        
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        
                        <?php echo form_open(base_url() . 'login/enviar', $attributes); ?>

                            <h3 class="text-left text-secondary pt-1">Login</h3>
                            <h6 class="pb-3">Backoffice e-Enroll</h6>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">@</span>
                                </div>
                                <input type="text" id="login" name="login" class="form-control" placeholder="Login" aria-label="login" aria-describedby="basic-addon1">
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Senha" aria-label="senha" aria-describedby="basic-addon1">
                            </div>

                            <div class="g-recaptcha" data-sitekey="6LcNVwsqAAAAANke5h78JPoWG5UYmfORb4A3Y4qH" data-action="Login" data-callback="callback"></div>
                            
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-dark mt-3" value="Entrar">
                            </div>

                            <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>
                        
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>