<!DOCTYPE html>
<html lang="en"><head>
    <title><?php echo NAME_SITE; ?></title>

    <meta charset="utf-8">
    <link rel="shortcut icon" href="https://boladeneve.com/sites/default/files/bola-icons_0_0.png" type="image/png" />
    
    <base href="<?php echo base_url() ?>" />
    
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet" type="text/css" />
    
    <?php echo $CssProjects ?>
    
    <?php echo $Themes ?>
    
    <?php echo $Javascripts ?>
    
</head>
<body>

<div id="content">

	<div id="templatemo_header_wrapper">
		<div id="templatemo_header">

            <div class="td_Title" style="padding-top:20px">
			
				<img src="<?php echo base_url(); ?>assets/images/wmb_logo.jpg" width="100" height="100" style="border: 1px #cecece solid">
				
			</div>
            
            <div class="td_Perfil">
                
				<?php echo get_picture('', '50', '50'); ?>
                
                <p><a href="<?php echo base_url(); ?>perfil"><?php echo LimitaStr($Sessions['strNome'], 20); ?></a></p>
                <p><a href="<?php echo base_url(); ?>perfil">Administrador</a></p>
				<p><a href="<?php echo base_url(); ?>perfil">Boladeneve.com</a></p>
            </div>
		</div>
	</div>

	<div id="templatemo_menu_wrapper">
		<div id="templatemo_menu">
        	
            <?php echo get_menu($Sessions['intTipo']); ?>
            
        </div>
	</div>
	
	<div id="templatemo_content_wrapper">
		<div id="templatemo_content">
    
            <div class="content_pages">
                <div class="Moldura_Internas">
                    <div id="ConteudoModulo">
