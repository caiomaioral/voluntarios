<?php 

    echo $AddCss;
    echo $AddJavascripts;
                            
    $attributes = array('id' => 'FormX', 'name' => 'FormX');

    echo form_open(base_url(), $attributes); 
    
?>

<div class="jumbotron">
    <div class="col-sm-12 mx-auto">
        <div class="form-group">
            
            <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="td_Titulo">
                        <h5>Elenco do Pyngaiada</h5>
                    </div>    
                </td>
            </tr>
            <tr>
                <td><a class="btn btn-secondary" href="<?php echo base_url(); ?>atletas/incluir" role="button">Cadastrar novo atleta</a></td>
            </tr>            
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td>
                    <div id="container" style="height: auto">
                        <div class="demo_jui" style="height: auto">
                        
                            <table id="examples" width="100%" cellpadding="0" cellspacing="0" border="0" class="display">
                            <thead>
                            <tr>
                                <th width="100" height="25">Ações</th>
                                <th width="350" height="25">Nome</th>
                                <th width="150" height="25">Nascimento</th>
                                <th width="200" height="25">E-mail</th>
                                <th width="150" height="25">Posição</th>
                                <th width="100" height="25">Situação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="gradeA">
                                
                            </tr>
                            </tbody>
                            </table>
                            
                        </div>
                    </div>

                </td>
            </tr>
            </table>            

        </div>             
    </div>
</div>

<!-- Modal -->
<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Exclusão de Atletas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja apagar o atleta?</p>
                <p><strong>Atenção, esta ação é irreversível!</strong></p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="DeleteForever" type="button" class="btn btn-primary">Excluir</button>

                <input id="IdAtleta" name="IdAtleta" type="hidden" value="">

            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>