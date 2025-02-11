<?php

	echo $AddJavascripts;

	$attributes = array('id' => 'FormX', 'name' => 'FormX', 'accept-charset' => 'utf-8');

	echo form_open(base_url() . 'payment', $attributes); 

?>

<div class="container bg-white border rounded">
	<div class="row mt-4 ml-2 mb-3 mr-2">
		<div class="col-sm-12">

			<div class="card mt-4 mb-3">
				<div class="card-body">
					<h2><?php echo $AddBody->titulo; ?></h2>
					<p><i class="far fa-calendar-alt"></i> Evento a ser realizado no dia <?php echo data_us_to_br($AddBody->data_evento); ?>.</p>
				</div>
			</div>

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
						<span class="bs-stepper-circle bg-dark">1</span>
						<span class="bs-stepper-label">Informações</span>
					</button>
					</div>
					<div class="line"></div>
					<div class="step" data-target="#information-part">
					<button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
						<span class="bs-stepper-circle">2</span>
						<span class="bs-stepper-label">Pagamento</span>
					</button>
					</div>
				</div>
				<div class="bs-stepper-content">
					<!-- your steps content here -->
					<div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger"></div>
					<div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger"></div>
				</div>
			</div>

			<h4 class="mb-3">Informações do(s) participante(s)</h4>

			<div id="add">
				<?php for($x = 1; $x <= $Ingressos; $x++){ ?>

					<div class="form-row">
						<div class="col-md-6 mb-3">
							<input name="CPF_Adicional[<?php echo $x; ?>]" placeholder="CPF" maxlength="18" type="text" class="form-control CPF_Adicional">
						</div>
						
						<div class="col-md-6 mb-3">
							<input name="Nome_Adicional[<?php echo $x; ?>]" placeholder="Nome" maxlength="100" type="text" class="form-control Nome_Adicional"> 
						</div>
					</div>

				<?php } ?>
			</div>

			<hr>  			
			
			<h4 class="mb-3">Dados do titular do cartão</h4>

			<div class="form-row">
				<div class="col-md-6 mb-3">
					<input id="CPF" name="CPF" placeholder="CPF" maxlength="18" type="text" class="form-control">
				</div>

				<div class="col-md-6 mb-3">
					<input id="Nome" name="Nome" placeholder="Nome" maxlength="100" type="text" class="form-control"> 
				</div>

			</div>

			<div class="form-row">
				<div class="col-md-3 mb-3">
					<input id="Celular" name="Celular" placeholder="Celular" type="text" class="form-control">
				</div>
				<div class="col-md-6 mb-3">
					<input id="Cidade" name="Cidade" placeholder="Cidade" type="text" class="form-control">
				</div>
				<div class="col-md-3 mb-3">
					<?php

						$js = 'id="UF" class="custom-select"';

						echo form_dropdown('UF', $AddUF, set_value('UF'), $js);       

					?>		
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12 mb-4">
					<input id="Email" name="Email" placeholder="E-mail para recebimento do comprovante" maxlength="100" type="text" class="form-control">
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert">
						Atesto para os devidos fins que não apresento qualquer sintoma de tosse persistente, falta de ar, desconforto respiratório e gripe/resfriado, dores pelo corpo ou qualquer outro desconforto. Da mesma forma, declaro que não sou pertencente a nenhum grupo de risco, tais como, pessoa acima de 60 (sessenta) anos, hipertenso, diabético, gestante e imunodeprimido.
					</div>
				</div>
			</div>

			<div class="form-check">
				<input id="Coronavirus" name="Coronavirus" class="form-check-input" type="checkbox">
				<label class="form-check-label" for="Coronavirus">
				Eu aceito os termos acima
				</label>
			</div>			

			<div class="form-group text-center mt-5">
				<button id="Enviar_Pagamento" type="submit" class="btn btn-dark btn-lg">Prosseguir para pagamento</button>
			</div>

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

<input id="id_evento" name="id_evento" type="hidden" value="<?php echo $id_Evento; ?>">
<input id="titulo" name="titulo" type="hidden" value="<?php echo $Titulo; ?>">
<input id="url" name="url" type="hidden" value="<?php echo $URL; ?>">
<input id="valor" name="valor" type="hidden" value="<?php echo $Valor; ?>">
<input id="transporte" name="transporte" type="hidden" value="<?php echo $Valor_Transporte; ?>">
<input id="ingressos" name="ingressos" type="hidden" value="<?php echo $Ingressos; ?>">
<input id="quer_transporte" name="quer_transporte" type="hidden" value="<?php echo $Quer_Transporte; ?>">
<input id="numero_parcelas" name="numero_parcelas" type="hidden" value="<?php echo $Parcelas; ?>">
<input id="valor_total" name="valor_total" type="hidden" value="<?php echo $Valor_Total; ?>">

<?php echo form_close(); ?>