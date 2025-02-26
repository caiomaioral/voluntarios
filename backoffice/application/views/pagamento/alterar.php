<?php

    echo $Javascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX', 'autocomplete' => 'off');

    echo form_open(base_url() . 'financeiro/salvar', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12"> 

        <input type="text" style="display:none">
        <input type="password" style="display:none">  

        <h5>Alteração de qualquer despesa, compra de bola, churrascos, e ou doações e etc.</h5>

        <hr  />   

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>financeiro">Pagamento</a></li>
                <li class="breadcrumb-item active" aria-current="page">Alterar Lançamento</li>
            </ol>
        </nav>        

        <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>

        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Lançamento</strong></div>

            <div class="row">
                <div class="col-md-9 mb-3">
                    <input id="Descricao" name="Descricao" placeholder="Descrição" maxlength="100" type="text" class="form-control" value="<?php echo set_value('Descricao', mb_strtoupper($AddLancamento->Descricao)); ?>"> 
                </div>
                
                <div class="col-md-3 mb-3">
                    <input id="Valor" name="Valor" placeholder="Valor" type="text" class="form-control money" value="<?php echo set_value('Valor', num_to_user($AddLancamento->Valor)); ?>">                
                </div>                
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php

                        $js = 'id="Natureza" class="form-control"';

                        echo form_dropdown('Natureza', array('' => 'Selecione a Natureza de Operação', 'C' => 'CRÉDITO', 'D' => 'DÉBITO'), set_value('Natureza', $AddLancamento->NaturezaOperacao), $js);                            

                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-secondary">Salvar dados ...</button>
                </div>
            </div>
            
            <input id="IdPagamento" name="IdPagamento" type="hidden" value="<?php echo $AddLancamento->Id; ?>">

        </fieldset>

        <?php echo form_close(); ?>
        
    </div>
</div>