<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'autocomplete' => 'off');

    echo form_open(base_url() . 'atletas/enviar', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Por favor preencha o cadastro e deixe atualizado as informações para a temporada de <?php echo date('Y'); ?>.</h5>

        <hr  />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>atletas">Atletas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Incluir Atleta</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Atleta</strong></div>

            <div class="row">
                <div class="col-md-10 mb-3">
                    <input id="Nome" name="Nome" placeholder="Nome" maxlength="100" type="text" class="form-control" value="<?php echo set_value('Nome'); ?>"> 
                </div>
                
                <div class="col-md-2 mb-3">
                    <input id="Nascimento" name="Nascimento" placeholder="Nascimento" type="text" class="form-control date" value="<?php echo set_value('Nascimento'); ?>">                
                </div>                
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <input id="Apelido" name="Apelido" placeholder="Apelido" type="text" class="form-control" value="<?php echo set_value('Apelido'); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <input id="Email" name="Email" placeholder="E-mail" type="text" class="form-control email" value="<?php echo set_value('Email'); ?>">
                </div>                
            </div>  

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Posicao" class="form-control"';

                        echo form_dropdown('Posicao', array('' => 'Selecione a Posição', 'GOLEIRO' => 'GOLEIRO', 'ZAGUEIRO' => 'ZAGUEIRO', 'LATERAL' => 'LATERAL', 'VOLANTE' => 'VOLANTE', 'MEIA' => 'MEIA', 'ATACANTE' => 'ATACANTE', 'CENTROAVANTE' => 'CENTROAVANTE'), set_value('Posicao'), $js);                            

                    ?>
                </div>
            </div>
            
            <div class="alert alert-warning" role="alert"><strong>Por padrão a senha de acesso sempre será 123456 :)</strong></div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-secondary">Incluir dados ...</button>
                </div>
            </div>             

        </fieldset>

        <?php echo form_close(); ?>
        
    </div>
</div>