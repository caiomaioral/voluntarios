<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'autocomplete' => 'off');

    echo form_open(base_url() . 'competicoes/enviar_jogo', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Preencha os dados do jogo do dia.</h5>

        <hr  />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>competicoes">Competições</a></li>
                <li class="breadcrumb-item active" aria-current="page">Incluir Jogo</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Jogo</strong></div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <input id="Data" name="Data" placeholder="Data do Jogo" maxlength="100" type="text" class="form-control date" value="<?php echo set_value('Data'); ?>"> 
                </div>

                <div class="col-md-5 mb-3">
                    <input id="Mandante" name="Mandante" placeholder="Nome do Mandante" type="text" class="form-control" value="<?php echo set_value('Mandante'); ?>">
                </div>

                <div class="col-md-5 mb-3">
                    <input id="Visitante" name="Visitante" placeholder="Nome do Visitante" type="text" class="form-control email" value="<?php echo set_value('Visitante'); ?>">
                </div>                 
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <input id="GolsMandante" name="GolsMandante" placeholder="Gols do Mandante" type="text" class="form-control" value="<?php echo set_value('GolsMandante'); ?>">
                </div>
                X   
                <div class="col-md-3 mb-3">
                    <input id="GolsVisitante" name="GolsVisitante" placeholder="Gols do Visitante" type="text" class="form-control email" value="<?php echo set_value('GolsVisitante'); ?>">
                </div>                
            </div>  

            <div class="row">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-secondary">Incluir dados ...</button>
                </div>
            </div>             

        </fieldset>

        <?php echo form_close(); ?>
        
    </div>
</div>