<!doctype html>
<html lang="pt">
<head>
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <?php echo $Themes; ?>
    
    <?php echo $CssProjects; ?>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/scripts/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

</head>

<body>
  
<div class="container-lg">

  <a class="navbar-brand pt-3 mb-3" href="<?php echo base_url(); ?>home">
      <img src="<?php echo base_url(); ?>assets/images/pyngaiada.png" alt="" loading="lazy" />
  </a>

  <nav class="navbar navbar-expand-md navbar-dark bg-dark rounded">
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarCollapse">
      
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link <?php echo ($class == 'home')? 'active' : ''; ?>" href="<?php echo base_url(); ?>home">Home</a>
      </li>

      <?php if($this->session->userdata('Admin') == 1){ ?>

          <li class="nav-item">
            <a class="nav-link <?php echo ($class == 'atletas')? 'active' : ''; ?>" href="<?php echo base_url(); ?>atletas">Atletas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($class == 'financeiro')? 'active' : ''; ?>" href="<?php echo base_url(); ?>financeiro">Financeiro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($class == 'jogo')? 'active' : ''; ?>" href="<?php echo base_url(); ?>jogo">Dia do Jogo</a>
          </li>                     

      <?php } ?>

      <li class="nav-item">
        <a class="nav-link <?php echo ($class == 'elenco')? 'active' : ''; ?>" href="<?php echo base_url(); ?>elenco">Elenco</a>
      </li>      
      <li class="nav-item">
        <a class="nav-link <?php echo ($class == 'competicoes')? 'active' : ''; ?>" href="<?php echo base_url(); ?>competicoes">Competições</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>logoff">Sair</a>
      </li> 
    </ul>
    </div>
  </nav>