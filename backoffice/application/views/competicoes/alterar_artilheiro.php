<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'autocomplete' => 'off');

    echo form_open(base_url() . 'competicoes/salvar_artilheiro', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Altere os dados do artilheiro.</h5>

        <hr  />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>competicoes">Competições</a></li>
                <li class="breadcrumb-item active" aria-current="page">Alterar Artilheiro</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Artilheiro</strong></div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?php

                        $js = 'id="Jogador" class="form-control"';

                        echo form_dropdown('Jogador', $AddAtletas, set_value('Jogador', $AddAtleta->IdAtleta), $js);                            

                    ?>
                </div>

                <div class="col-md-3 mb-3">
                    <input id="Gols" name="Gols" placeholder="Quantidade de Gols" type="text" class="form-control email" value="<?php echo set_value('Gols', $AddAtleta->Gols); ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <input id="Assistencias" name="Assistencias" placeholder="Quantidade de Assistências" type="text" class="form-control email" value="<?php echo set_value('Assistencias', $AddAtleta->Assistencias); ?>">
                </div>                 
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Jogo" class="form-control"';

                        echo form_dropdown('Jogo', $AddJogo, set_value('Jogo', $AddAtleta->IdJogo), $js);                            

                    ?>
                </div>
            </div>             

            <div class="row">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-secondary">Salvar dados ...</button>
                </div>
            </div>
            
            <input id="IdArtilheiro" name="IdArtilheiro" type="hidden" value="<?php echo $AddAtleta->Id; ?>">

        </fieldset>

        <?php echo form_close(); ?>
        
    </div>
</div>