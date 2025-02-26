<?php 

	$attributes = array('id' => 'FormF', 'name' => 'FormF');

	echo form_open(base_url() . 'feedbacks/enviar', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12 mx-auto">

        <h3 class="text-left text-dark mb-3">Sugestões de Melhorias para o nosso Racha.</h3>

        <div class="input-group mb-3">
            <textarea id="Sugestao" name="Sugestao" class="form-control" rows="5"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-secondary" value="Enviar Sugestão :)">
        </div>

        <div class="alert alert-success mb-5" role="alert"><strong><?php echo $this->session->userdata('Nome'); ?></strong>, sua sugestão foi enviada com sucesso!</div>

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

    </div>
</div>        

<?php echo form_close(); ?>