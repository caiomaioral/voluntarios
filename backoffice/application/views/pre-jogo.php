<?php 

    echo $AddJavascripts;
    
?>

<div class="jumbotron">
    <div class="col-sm-12 mx-auto">
        <div class="row">
            <div class="col-md-12 mb-0">

                <h5 class="mb-3">Check-in de atletas para o dia <?php echo date('d/m/Y'); ?></h5>
                    
                <div class="alert alert-success" role="alert">
                    <strong>Chegou a hora do jogo!!</strong> Confirme clicando no botão abaixo para gerarmos a escalação do nosso time!!
                </div>

                <a class="btn btn-info mb-3" href="<?php echo base_url(); ?>jogo/escalar" role="button">Cadastrar / Acessar uma Sumula de Jogo</a>
                        
            </div>
        </div>             
    </div>
</div>