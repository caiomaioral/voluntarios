<?php 

    echo $AddCss;
    echo $AddJavascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX');

    echo form_open_multipart(base_url() . 'upload/excel', $attributes); 

?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Carga de Colaboradores</h3>
    </div>

    <div class="table-responsive">

        <div class="alert alert-primary" role="alert">
            O primeiro passo é obter uma planilha no formato <strong>XLSX</strong> ou <strong>XLS</strong>, onde a primeira coluna conterá exclusivamente os e-mails das pessoas, preferencialmente em letras minúsculas e sem espaços.<br><br>Após carregar a planilha, cada um dos e-mails será enviado com um link para que o usuário possa atualizar seu cadastro, além de receber o termo para assinatura por e-mail.
        </div>

        <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile" name="customFile" accept=".xlsx,.xls">
            <label class="custom-file-label" for="customFile" data-browse="Selecionar Arquivo">Selecione um arquivo XLSX</label>
        </div> 

        <?php echo validation_errors('<div class="alert alert-danger mt-3" role="alert">', '</div>'); ?>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-dark mt-3" value="Carregar Dados">
        </div>        
        
    </div>
</main>

<?php echo form_close(); ?>