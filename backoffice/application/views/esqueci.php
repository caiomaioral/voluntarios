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
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
</head>

<body>

    <?php $attributes = array('id' => 'FormX', 'name' => 'FormX', 'class' => 'form'); ?>
    
    <div id="login">
        <h3 class="text-center text-white pt-5">
            <img src="<?php echo base_url(); ?>assets/images/pyngaiada.png" width="108" height="132" alt="Logo: Pyngaiada Futebol Resenha" />
        </h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        
                        <?php echo form_open(base_url() . 'esqueci/enviar', $attributes); ?>

                            <h3 class="text-left text-secondary pt-1">Login</h3>
                            <h6 class="pb-3">Pyngaiada Futebol Resenha</h6>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">@</span>
                                </div>
                                <input type="text" id="user_email" name="email" class="form-control" placeholder="E-mail de login" aria-label="login" aria-describedby="basic-addon1">
                            </div>

                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-dark" value="Enviar">
                                <a class="btn btn-secondary" href="<?php echo base_url(); ?>login" role="button">Voltar</a>
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