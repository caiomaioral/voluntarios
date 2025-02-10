<?php

	echo $AddCss;	
	echo $AddJavascript;

	$attributes = array('id' => 'FormX', 'name' => 'FormX', 'accept-charset' => 'utf-8');

	echo form_open(base_url() . 'cadastro/processar', $attributes); 

?>

<script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>

<div class="overlay"></div>

<br>

<h4 class="mb-3">Para seguir com seu cadastro, preencha seus dados</h4>

<div class="row">
	<div class="col-md-6 mb-3">
		<input id="Nome" name="Nome" placeholder="Nome" maxlength="60" type="text" class="form-control" value="<?php echo set_value('Nome'); ?>" /> 
	</div>
	
	<div class="col-md-6 mb-3">
		<input id="CPF" name="CPF" placeholder="CPF" maxlength="18" type="text" class="form-control" value="<?php echo set_value('CPF'); ?>" />
	</div>
</div>

<div class="row">
	<div class="col-md-6 mb-3">
		<input id="Email" name="Email" placeholder="E-mail" maxlength="100" type="text" class="form-control" value="<?php echo set_value('Email'); ?>" />
	</div>
	
	<div class="col-md-6 mb-3">
		<input id="Telefone" name="Telefone" placeholder="Telefone" type="text" class="form-control" value="<?php echo set_value('Telefone'); ?>" /> 
	</div>
</div>

<div class="row">
	<div class="col-md-12 mb-3">

	</div>
</div>

<div class="row">
	<div class="col-md-12 mb-3">

		<div class="custom-control custom-switch">
			<input type="checkbox" name="Termos" class="custom-control-input card-checkbox" id="customSwitch1" value="1">
			<label class="custom-control-label" for="customSwitch1">
				Eu concordo de receber por e-mail os termos e condições para assinatura.
			</label>
		</div><br>
		
		<div class="alert alert-warning" role="alert">
			Estou ciente de que os Termos e Condições serão enviados para meu e-mail para assinatura digital. Caso eu concorde com os termos, comprometo-me a assiná-los digitalmente. Se optar por não assinar, estarei considerando que não aceitei os Termos e Condições
		</div>		
	</div>
</div>

<br>

<div class="g-recaptcha" data-sitekey="6LcNVwsqAAAAANke5h78JPoWG5UYmfORb4A3Y4qH" data-action="Doar" data-callback="callback"></div>

<br>

<div class="form-group text-center">
	<button id="enviar_dados" type="submit" class="btn btn-dark" disabled>Continuar o Cadastro</button>
</div>

<?php echo form_close(); ?>