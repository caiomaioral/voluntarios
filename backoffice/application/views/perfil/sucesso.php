<?php 

	$attributes = array('id' => 'FormX', 'name' => 'FormX');

	echo form_open(base_url() . 'perfil/salvar', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12 mx-auto">

        <h3 class="text-left text-dark">Utilize os campos abaixo para alterar tua senha.</h3>
        <h6 class="pb-3">A nova senha requer um número mínimo de 6 caracteres.</h6>

        <div class="input-group mb-3">
            <input id="OldPassword" name="OldPassword" type="password" class="form-control" placeholder="Senha antiga" aria-label="OldPassword" aria-describedby="basic-addon1">
        </div>

        <div class="input-group mb-3">
            <input id="NewPassword" name="NewPassword" type="password" class="form-control" placeholder="Nova senha" aria-label="senha" aria-describedby="basic-addon1"> 
        </div>

        <div class="input-group mb-3">
            <input id="ConfirmPassword" name="ConfirmPassword" type="password" class="form-control" placeholder="Confirme a nova senha" aria-label="senha" aria-describedby="basic-addon1">
        </div>        

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-secondary" value="Alterar senha">
        </div>

        <div class="alert alert-success mb-5" role="alert"><strong><?php echo $this->session->userdata('Nome'); ?></strong>, sua senha foi alterada com sucesso!</div>

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

    </div>
</div>        

<?php echo form_close(); ?>