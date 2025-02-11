<?php

	echo $AddJavascripts;	

	$attributes = array('id' => 'FormX', 'name' => 'FormX', 'accept-charset' => 'utf-8');

	echo form_open(base_url() . 'payment', $attributes); 

?>

<div class="container bg-white border rounded">
	<div class="row mt-4 ml-2 mb-3 mr-2">
		<div class="col-sm-12">

			<div class="alert alert-warning mb-4" role="alert">
				<div class="row">
					<div class="col-sm-1"><h3 id="countdown"></h3></div>
					<div class="col-sm-11">Por favor, complete o formulário abaixo no prazo máximo estabelecido ao lado. Depois deste período sua reserva será liberada para venda novamente.</div>
				</div>
			</div>	

			<div class="bs-stepper">
				<div class="bs-stepper-header" role="tablist">
					<!-- your steps here -->
					<div class="step" data-target="#logins-part">
					<button type="button" class="step-trigger" role="tab" aria-controls="logins-part" id="logins-part-trigger">
						<span class="bs-stepper-circle bg-dark">2</span>
						<span class="bs-stepper-label">Pagamento</span>
					</button>
					</div>
					<div class="line"></div>
					<div class="step" data-target="#information-part">
					<button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
						<span class="bs-stepper-circle">3</span>
						<span class="bs-stepper-label">Finalizar</span>
					</button>
					</div>
				</div>
				<div class="bs-stepper-content">
					<!-- your steps content here -->
					<div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger"></div>
					<div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger"></div>
				</div>
			</div>

			<h4 class="mb-3">Informações do Pedido</h4>

			<div class="card text-white bg-secondary mb-2">
				<div class="card-header mt-1"><h4 class="card-title">Numero: <?php echo inclui_zero_esq($Pedido); ?></h4></div>
				<div class="card-body">
					<p class="card-text">Titular do cartão: <strong><?php echo $Pagante; ?></strong></p>
					<p class="card-text">CPF do titular: <strong><?php echo formatCPF_CNPJ($CPF); ?></strong></p>
					<p class="card-text">E-mail de envio do comprovante: <strong><?php echo $Email; ?></strong></p>
					<p class="card-text">Quantidade de ingressos: <strong><?php echo $Ingressos; ?></strong></p>

					<?php if($Quer_Transporte == 'on'){ ?>

						<p class="card-text">Adquiriu transporte? <strong>SIM</strong></p>
						<p class="card-text">Valor do transporte por ingresso: <strong><?php echo num_to_user($Valor_Transporte); ?></strong></p>

					<?php } ?>

				</div>
			</div>
			
			<h1><span id="total" class="badge badge-pill badge-dark mt-3 mb-3"><i class="far fa-money-bill-alt"></i> Valor total de R$ <?php echo num_to_user($Valor_Total); ?></span></h1>
					
			<h4><span id="parcelas" class="badge badge-pill badge-dark py-3">
				<?php 

					if($Parcelas == 1)
					{
						echo 'Pagamento à vista';
					}	
					else
					{
						echo 'Ou ' . $Parcelas . ' parcelas de R$ ' . num_to_user($Valor_Total / $Parcelas);
					}
				?>
			</span></h4>			

			<br><hr> 

			<div class="form-group text-center mt-5">
				<button id="clique_granito" type="button" class="btn btn-dark btn-lg" data-toggle="modal" data-target="#paymentModalCenter">Clique para pagar</button>
			</div>

		</div>
	</div>
</div>

<!-- Div Ajax -->
<div class="ajax" style="padding-top: 20%; display:none">
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-light" style="width: 5rem; height: 5rem;" role="status">
            <span class="sr-only">Finalizando a operação...</span>
        </div>
    </div>
</div>
	
<!-- Modal -->
<div class="reveal modal fade bd-example-modal-lg" id="timerModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
			<div class="modal-body">
				
				<h4 class="mb-3 text-center">O tempo para a compra expirou</h4>
				<p class="mb-0 text-center">Isso é necessário para que uma reserva não fique presa e possa estar disponível para compra novamente.</p>
				<p class="mb-2 text-center">Você pode recomeçar a compra clicando no botão abaixo.</p>
				
			</div>
			<div class="modal-footer">
				<button id="close_timer" type="button" class="btn btn-dark btn-lg text-center">Voltar</button>
			</div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="reveal modal fade bd-example-modal-lg" id="paymentModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-white" id="exampleModalCenterTitle" style="font-size: 20px">Preencha para pagar</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body">
				<div class="form-group">
					<label for="installments" class="col-sm-2 col-form-label"><strong>PARCELAS</strong></label>
					<div class="col-sm-6">

						<select id="installments" name="installments" class="form-control">
						<option value="" selected="select">Selecione quantidade de parcelas</option>
						<?php

							$Legenda  =  '';
							$Select   =  '';
							
							for($x = 1; $x <= $Parcelas; $x++)
							{
								if($x == 1)
								{
									$Legenda = 'Pagamento à vista de R$ ' . num_to_user($Valor_Total);
								}
								else
								{
									$Legenda = $x . ' parcelas sem juros de R$ ' . num_to_user($Valor_Total / $x);
								}

								$Select .= '<option value="'.$x.'">'.$Legenda.'</option>';
							}

							echo $Select;

						?>
						</select>
					</div>
				</div>			
				<iframe id="pago_iframe" src="about:blank" style="border:none; height: 490px; width: 100%; padding-top:8px;padding-left:14px"></iframe>  
			</div>
			<div class="modal-footer">
				<button id="clique_fechamento" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				<button id="clique_pagamento" type="button" class="btn btn-primary" style="background-color: #000">Clique para Finalizar</button>
			</div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="reveal modal fade bd-example-modal-lg" id="confirmPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
			<div class="modal-body">
				
				<h4 class="mb-3 text-center">Dados enviados com sucesso :)</h4>
				<p class="mb-0 text-center">Seus dados foram enviados para a operadora do cartão!</p>
				<p class="mb-0 text-center">Em breve você receberá por e-mail sua confirmação de pagamento.</p>
				<p class="mb-2 text-center">Verifique seu e-mail para checar o status.</p>
				
			</div>
			<div class="modal-footer">
				<button id="close_timer" type="button" class="btn btn-dark btn-lg text-center">Voltar</button>
			</div>
        </div>
    </div>
</div>

<input id="id_evento" name="id_evento" type="hidden" value="<?php echo $id_Evento; ?>">
<input id="titulo" name="titulo" type="hidden" value="<?php echo $Titulo; ?>">
<input id="url" name="url" type="hidden" value="<?php echo $URL; ?>">
<input id="valor" name="valor" type="hidden" value="<?php echo $Valor; ?>">
<input id="transporte" name="transporte" type="hidden" value="<?php echo $Valor_Transporte; ?>">
<input id="ingressos" name="ingressos" type="hidden" value="<?php echo $Ingressos; ?>">
<input id="quer_transporte" name="quer_transporte" type="hidden" value="<?php echo $Quer_Transporte; ?>">
<input id="numero_parcelas" name="numero_parcelas" type="hidden" value="<?php echo $Parcelas; ?>">
<input id="valor_total" name="valor_total" type="hidden" value="<?php echo $Valor_Total; ?>">

<!-- GRANITO -->
<input type="hidden" id="order_number" name="order_number" value="<?php echo $Pedido; ?>"> 

<?php echo form_close(); ?>