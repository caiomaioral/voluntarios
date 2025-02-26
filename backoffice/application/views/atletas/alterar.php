<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX');

    echo form_open(base_url() . 'atletas/salvar', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Alteração de atleta da temporada de <?php echo date('Y'); ?>.</h5>

        <hr />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>atletas">Atletas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Alterar Atleta</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Atleta</strong></div>

            <div class="row">
                <div class="col-md-10 mb-3">
                    <input id="Nome" name="Nome" placeholder="Nome" maxlength="100" type="text" class="form-control" value="<?php echo set_value('Nome', mb_strtoupper($AddAtleta->Nome)); ?>"> 
                </div>
                
                <div class="col-md-2 mb-3">
                    <input id="Nascimento" name="Nascimento" placeholder="Nascimento" type="text" class="form-control date" value="<?php echo set_value('Nascimento', data_us_to_br($AddAtleta->DataNascimento)); ?>">                
                </div>                
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <input id="Apelido" name="Apelido" placeholder="Apelido" type="text" class="form-control" value="<?php echo set_value('Apelido', mb_strtoupper($AddAtleta->Apelido)); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <input id="Email" name="Email" placeholder="E-mail" type="text" class="form-control email" value="<?php echo set_value('Email', mb_strtolower($AddAtleta->Email)); ?>">
                </div>                
            </div> 

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Posicao" class="form-control"';

                        echo form_dropdown('Posicao', array(0 => 'Selecione a Posição', 'GOLEIRO' => 'GOLEIRO', 'ZAGUEIRO' => 'ZAGUEIRO', 'LATERAL' => 'LATERAL', 'VOLANTE' => 'VOLANTE', 'MEIA' => 'MEIA', 'ATACANTE' => 'ATACANTE', 'CENTROAVANTE' => 'CENTROAVANTE'), set_value('Posicao', $AddAtleta->Posicao), $js);                            

                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Inativo" class="form-control"';

                        echo form_dropdown('Inativo', array(0 => 'ATIVO', 1 => 'INATIVO'), set_value('Inativo', $AddAtleta->Inativo), $js);                            

                    ?>
                </div>
            </div>            
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-secondary">Salvar dados ...</button>
                </div>
            </div>             

            <input id="IdAtleta" name="IdAtleta" type="hidden" value="<?php echo $AddAtleta->Id; ?>">

        </fieldset>

        <?php echo form_close(); ?>
        
    </div>
</div>