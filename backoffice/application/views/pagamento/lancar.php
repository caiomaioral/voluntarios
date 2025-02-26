<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'autocomplete' => 'off');

    echo form_open(base_url() . 'financeiro/enviar', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Inclusão de qualquer despesa, compra de bola, churrascos, e ou doações e etc.</h5>

        <hr  />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>financeiro">Pagamento</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lançar</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Lançamento</strong></div>

            <div class="row">
                <div class="col-md-9 mb-3">
                    <input id="Descricao" name="Descricao" placeholder="Descrição" maxlength="100" type="text" class="form-control" value="<?php echo set_value('Descricao'); ?>"> 
                </div>
                
                <div class="col-md-3 mb-3">
                    <input id="Valor" name="Valor" placeholder="Valor" type="text" class="form-control money" value="<?php echo set_value('Valor'); ?>">                
                </div>                
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Natureza" class="form-control"';

                        echo form_dropdown('Natureza', array('' => 'Selecione a Natureza de Operação', 'C' => 'CRÉDITO', 'D' => 'DÉBITO'), set_value('Natureza'), $js);                            

                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Periodo" class="form-control"';

                        echo form_dropdown('Periodo', array('' => 'Selecione o Período', '1' => '1° Trimestre', '2' => '2° Trimestre', '3' => '3° Trimestre', '4' => '4° Trimestre'), set_value('Periodo'), $js);                            

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