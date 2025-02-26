<?php 

    echo $AddJavascripts;
    
?>

<div class="jumbotron">
    <div class="col-sm-12 mx-auto">
        <div class="row">
            <div class="col-md-12 mb-0">

                <h5 class="mb-3">Lista de Presença para o dia <?php echo date('d/m/Y'); ?></h5>
                    
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Elenco Oficial - Lista de Check-in
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">

                                    <a class="btn btn-success mb-3" data-toggle="modal" data-target="#show-escalacao" role="button">Simular Escalação</a>    
                                
                                    <div id="ListaAtletas"><?php echo $this->escalacao_model->Widget_Players($AddCheckIn); ?></div>

                                </div>
                            </div>
                        </div>
                        
                    </div> 
                </div>
            </div>
        </div>             
    </div>
</div>

<input id="IdJogo" name="IdJogo" type="hidden" value="<?php echo $AddCheckIn; ?>">

<!-- Modal -->
<div id="show-escalacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Simulador de Escalação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="Team">
                    <div class="alert alert-success" role="alert">
                        Escale seu time! Faça corretamente o check-in e clique no botão atualizar para ter uma sugestão de escalação!
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button id="UpdateList" type="button" class="btn btn-primary">Atualizar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

            </div>
        </div>
    </div>
</div>