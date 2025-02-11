<?php

	echo $AddJavascripts;	

	$attributes = array('id' => 'FormX', 'name' => 'FormX', 'accept-charset' => 'utf-8');

	echo form_open(base_url() . 'informations', $attributes); 

?>

<div class="container bg-white border rounded">

	<div class="card mt-4 ml-4 mb-2 mr-4">
		<div class="card-body">
			<h2><?php echo $AddBody->titulo; ?></h2>
			<p><i class="far fa-calendar-alt"></i> Evento a ser realizado no dia <?php echo data_us_to_br($AddBody->data_evento); ?>.</p>
		</div>
	</div>

	<div class="row mt-3 ml-2 mb-3 mr-2">
		<div class="col-sm-8">
			<div class="card text-center mb-3">
				<div class="card-header">
					<ul class="nav nav-tabs card-header-tabs">
						<li class="nav-item">
							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#sobre" role="tab" aria-controls="sobre" aria-selected="true">Sobre</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contato</a>
						</li>
					</ul>
				</div>
				<div class="tab-content mt-3 ml-3 mr-3" id="myTabContent">
					<div class="tab-pane fade show active" id="sobre" role="tabpanel" aria-labelledby="sobre-tab">
						
						<p class="text-left">
							<?php echo $AddBody->texto; ?>
						</p>

					</div>
					<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
						
						<p class="text-left"><a href="#" id="mailbutton" class="btn btn-dark">Dúvidas? Basta entrar em contato clicando aqui!</a></p>					
					
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">

			<div class="card bg-light mb-3">
				<div class="card-header"><i class="fas fa-ticket-alt"></i> Quantos ingressos deseja adquirir?</div>
				<div class="card-body">
					
					<select id="Ingressos" name="Ingressos" class="custom-select">
					<?php for($x = 1; $x <= $AddBody->adicionais; $x++){ ?>

						<option value="<?php echo $x; ?>"><?php echo $x; ?> ingresso<?php if($x==1){ echo ''; }else{ echo 's'; } ?></option>

					<?php } ?>
					</select>

					<?php if($AddBody->transporte != 0.00){ ?>
					
						<div class="custom-control custom-switch mt-3">
							<input id="Quer_Transporte" name="Quer_Transporte" type="checkbox" class="custom-control-input">
							<label class="custom-control-label" for="Quer_Transporte">Deseja incluir transporte?</label>
						</div>

						<h3><span class="badge badge-pill badge-info mt-3"><i class="fas fa-bus-alt"></i> R$ <?php echo num_to_user($AddBody->transporte); ?></span></h3>
					
					<?php } ?>

				</div>
			</div>

			<button type="submit" class="btn btn-dark mb-3">Comprar Ingressos</button>

			<div class="card bg-light mb-3">
				<div class="card-header"><i class="far fa-money-bill-alt"></i> R$ <?php echo num_to_user($AddBody->valor); ?> por pessoa.</div>
				<div class="card-body text-info">

					<h2><span id="total" class="badge badge-pill badge-dark mt-3 py-2"><i class="far fa-money-bill-alt"></i> Total R$ <?php echo num_to_user($AddBody->valor); ?></span></h2>
					
					<h5 id="total" class="card-title"></h5>
					<h4><span id="parcelas" class="badge badge-pill badge-dark py-2">
						<?php 

							if($AddBody->parcelas == 1)
							{
								echo 'Pagamento à vista';
							}	
							else
							{
								echo 'Ou ' . $AddBody->parcelas . ' parcelas de R$ ' . num_to_user($AddBody->valor / $AddBody->parcelas);
							}
						?>
					</span></h4>
				</div>
			</div>			
		</div>
	</div>
</div>

<input id="id_evento" name="id_evento" type="hidden" value="<?php echo $AddBody->id_evento; ?>">
<input id="titulo" name="titulo" type="hidden" value="<?php echo $AddBody->titulo; ?>">
<input id="url" name="url" type="hidden" value="<?php echo $AddBody->url; ?>">
<input id="valor" name="valor" type="hidden" value="<?php echo $AddBody->valor; ?>">
<input id="transporte" name="transporte" type="hidden" value="<?php echo $AddBody->transporte; ?>">
<input id="numero_parcelas" name="numero_parcelas" type="hidden" value="<?php echo $AddBody->parcelas; ?>">
<input id="valor_total" name="valor_total" type="hidden" value="">

<?php echo form_close(); ?>