<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'autocomplete' => 'off');

    echo form_open(base_url() . 'competicoes/enviar_artilheiro', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Preencha os dados do artilheiro.</h5>

        <hr  />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>competicoes">Competições</a></li>
                <li class="breadcrumb-item active" aria-current="page">Incluir Artilheiro</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Artilheiro</strong></div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?php

                        $js = 'id="Jogador" class="form-control"';

                        echo form_dropdown('Jogador', $AddAtletas, set_value('Jogador'), $js);                            

                    ?>
                </div>

                <div class="col-md-3 mb-3">
                    <input id="Gols" name="Gols" placeholder="Quantidade de Gols" type="text" class="form-control email" value="<?php echo set_value('Gols'); ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <input id="Assistencias" name="Assistencias" placeholder="Quantidade de Assistências" type="text" class="form-control email" value="<?php echo set_value('Assistencias'); ?>">
                </div>                 
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Jogo" class="form-control"';

                        echo form_dropdown('Jogo', $AddJogo, set_value('Jogo'), $js);                            

                    ?>
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