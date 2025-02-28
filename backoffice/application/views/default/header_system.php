<!doctype html>
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

</head>

<body>
  
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="https://www.siafsolutions.com.br/" target="_blank"><img src="<?php echo base_url(); ?>assets/images/logo_siafsolutions_02.png" alt="" width="175" height="24"></a>
    
    <!--
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <a class="nav-link" href="#">Sair</a>
      </li>
    </ul>
    -->
</nav>



<div class="container-fluid">
  <div class="row">

    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
    
      <div class="sidebar-sticky">
          <ul class="nav flex-column">
              
              <li class="nav-item">
                <a class="nav-link active" href="https://boladeneve.com/" target="_blank">
                    <img class="mb-2" src="<?php echo base_url(); ?>assets/images/bola-de-neve-logo-preto.png" alt="" width="100" height="88">
                </a>
              </li>          
          
              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url(); ?>home">
                    <span data-feather="home"></span>
                    Dashboard <span class="sr-only">(atual)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>colaboradores">
                    <span data-feather="users"></span>
                    Colaboradores
                </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>relatorios">
                  <span data-feather="bar-chart-2"></span>
                  Relatórios
              </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>logoff">
                  <span data-feather="log-out"></span>
                  Sair
              </a>
              </li>
          </ul>

          <!--
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Relatórios</span>
              <a class="d-flex align-items-center text-muted" href="#">
              <span data-feather="plus-circle"></span>
              </a>
          </h6>
          <ul class="nav flex-column mb-2">
              <li class="nav-item">
              <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Neste mês
              </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Último trimestre
              </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Engajamento social
              </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Vendas do final de ano
              </a>
              </li>
          </ul>
          -->
          
        </div>
    </nav>

  </div>
</div>

<!-- Ícones -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>      