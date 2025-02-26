<?php

echo $AddJavascript;
echo $AddCss;

$attributes = array('id' => 'FormS', 'name' => 'FormS');

echo form_open(base_url() . 'financeiro', $attributes); 

?>

<script>

    function Mudar()
    {
        $('#FormS').submit();
    }

</script>

<div class="jumbotron">
    <div class="col-sm-12">

        <a class="btn btn-secondary mb-3" href="<?php echo base_url(); ?>financeiro/lancamento" role="button">Incluir Lançamentos</a>

        <select id="Natureza" name="Natureza" onchange="Mudar()" class="form-control mb-2">
        <option value="" <?php if($Natureza == '') echo 'selected' ?>>Selecione a Natureza de Operação</option>
        <option value="C" <?php if($Natureza == 'C') echo 'selected' ?>>CRÉDITOS</option>
        <option value="D" <?php if($Natureza == 'D') echo 'selected' ?>>DÉBITOS</option>
        </select>        
        
        <select id="Periodo" name="Periodo" onchange="Mudar()" class="form-control mb-2">
        <option value="0" <?php if($Periodo == 1) echo 'selected' ?>>Selecione o Período</option>
        <option value="1" <?php if($Periodo == 1) echo 'selected' ?>>1° Trimestre</option>
        <option value="2" <?php if($Periodo == 2) echo 'selected' ?>>2° Trimestre</option>
        <option value="3" <?php if($Periodo == 3) echo 'selected' ?>>3° Trimestre</option>
        <option value="4" <?php if($Periodo == 4) echo 'selected' ?>>4° Trimestre</option>
        </select>

        <select id="Pendentes" name="Pendentes" onchange="Mudar()" class="form-control mb-2">
        <option value="" <?php if($Pendentes == '') echo 'selected' ?>>Todos</option>
        <option value="1" <?php if($Pendentes == 1) echo 'selected' ?>>Pagos</option>
        <option value="2" <?php if($Pendentes == 2) echo 'selected' ?>>Pendentes</option>
        </select>        
        
        <?php echo $this->pagamento_model->Widget_General_Payment(date('Y')); ?>
        
    </div>
</div>        

<!-- Modal -->
<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Exclusão de Lançamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja apagar o lançamento?</p>
                <p><strong>Atenção, esta ação é irreversível!</strong></p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="DeleteForever" type="button" class="btn btn-primary">Excluir</button>

                <input id="IdPagamento" name="IdPagamento" type="hidden" value="">

            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>