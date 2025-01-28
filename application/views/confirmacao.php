<?php

    echo $AddCss;    
    echo $AddJavascript;

    $caracteres = array("(", ")", " ", "-");

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'accept-charset' => 'utf-8');

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>    
<script src="https://ecommerce.granitopagamentos.com.br/js/paymentmethodnonce.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

<style>
	.overlay {
		position: fixed;
		display: none;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0,0,0,0.5);
		z-index: 2;
		cursor: pointer;
	}
</style>

<div class="overlay"></div>

<?php echo form_open(base_url(), $attributes); ?>

    <h4 class="mb-3">Para seguir com sua doação, confirme seus dados e clique no botão abaixo</h4>
    
    <br />
    
    <h4 class="mb-3">Dados Cadastrais</h4>

    <div class="row">
        <div class="col-md-6 mb-3">
            <input id="CPF" name="CPF" placeholder="CPF" maxlength="18" type="text" class="form-control" value="<?php echo formatCPF_CNPJ($Body['CPF']); ?>" />
        </div>
        
        <div class="col-md-6 mb-3">
            <input id="Nome" name="Nome" placeholder="Nome" maxlength="60" type="text" class="form-control" value="<?php echo $Body['Nome']; ?>" /> 
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <input id="Email" name="Email" placeholder="E-mail" maxlength="100" type="text" class="form-control" value="<?php echo $Body['Email']; ?>" />
        </div>
        
        <div class="col-md-6 mb-3">
            <input id="Telefone" name="Telefone" placeholder="Telefone" type="text" class="form-control" value="<?php echo $Body['Telefone']; ?>" /> 
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <?php

                $js = 'id="Projeto" class="form-control"';		

                echo form_dropdown('Projeto', $AddProjeto, set_value('Projeto', $Body['IdProjeto']), $js);       

            ?>
        </div>
    </div>

    <br />			

    <h4 class="mb-3">Seu dizimo ou oferta</h4>

    <div class="form-group min-vw-50">
        <input id="Valor" name="Valor" placeholder="Valor da doação" minlength="6" maxlength="15" type="text" class="form-control" value="<?php echo num_to_user($Body['valor']); ?>" />
    </div>

    <br />						
    <hr />
    <br />
    
    <div class="form-group text-center">
        <button id="clique_granito" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Clique para Pagamento</button>
    </div>        

    <!-- GRANITO -->
    <input type="hidden" id="order_number" name="order_number" value="<?php echo $Body['Pedido']; ?>" /> 
    <input type="hidden" id="customer_name" name="customer_name" value="<?php echo $Body['Nome']; ?>" /> 
    <input type="hidden" id="customer_identity" name="customer_identity" value="<?php echo $Body['CPF']; ?>" />               

<?php echo form_close(); ?>

<script type="text/javascript">

	$(document).ready(function () {

		$('#Valor').mask('000.000.000.000.000,00', {reverse: true});
        $('#CPF').mask('999.999.999-99', {reverse: true});
        
	});

</script>

<!-- Modal -->
<div class="reveal modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle" style="font-size: 20px">Preencha para finalizar a doação</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            
            <iframe id="pago_iframe" src="about:blank" style="border:none; height: 440px; width: 100%"></iframe>  
            
        </div>
        <div class="modal-footer">
            <button id="clique_fechamento" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button id="clique_pagamento" type="button" class="btn btn-primary" style="background-color: #000">Clique para pagar</button>
        </div>
        </div>
    </div>
</div>