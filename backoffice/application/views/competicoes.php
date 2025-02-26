<?php 

    echo $AddCss;
    echo $AddJavascripts;

    $attributes = array('id' => 'FormX', 'name' => 'FormX');
    
?>
<div class="jumbotron">
    <div class="col-sm-12 mx-auto">
        <div class="row">
        
            <div class="col-md-12 mb-0">

                <h5 class="mb-3">Dados de Competição</h5>
                
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Jogos da Temporada
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">

                                <select id="Jogos" name="Jogos" class="form-control mb-3">
                                <option value="2023" <?php if($Jogos == 2023) echo 'selected' ?>>TEMPORADA 2023</option>
                                <option value="2024" <?php if($Jogos == 2024) echo 'selected' ?> selected>TEMPORADA 2024</option>
                                </select>                                 

                                <?php if($this->session->userdata('Admin') == 1){ ?><a class="btn btn-secondary mb-3" href="<?php echo base_url(); ?>competicoes/incluir_jogo" role="button">Cadastrar novo jogo</a><?php } ?>
                                
                                <div id="ListaJogos"><?php echo $this->competicoes_model->Widget_Matches(date('Y')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Artilheiros
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <select id="Temporada" name="Temporada" class="form-control mb-3">
                                <option value="2023" <?php if($Temporada == 2023) echo 'selected' ?>>ARTILHEIROS 2023</option>
                                <option value="2024" <?php if($Temporada == 2024) echo 'selected' ?> selected>ARTILHEIROS 2024</option>
                                <option value="2099" <?php if($Temporada == 2099) echo 'selected' ?>>ARTILHARIA GERAL</option>
                                </select>                                 

                                <?php if($this->session->userdata('Admin') == 1){ ?><a class="btn btn-secondary mb-3" href="<?php echo base_url(); ?>competicoes/incluir_artilheiro" role="button">Cadastrar artilharia / assistências</a><?php } ?>

                                <div id="ListaArtilheiros"><?php echo $this->competicoes_model->Widget_Artilheiros(date('Y'), null); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed text-left" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Assistências
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                                
                                <select id="Assistencias" name="Assistencias" class="form-control mb-3">
                                <option value="2024" <?php if($Assistencias == 2024) echo 'selected' ?> selected>ASSISTÊNCIAS 2024</option>
                                </select>                                 
                                
                                <div id="ListaAssistencias"><?php echo $this->competicoes_model->Widget_Assistencias(date('Y'), 'Home'); ?></div>
                            </div>
                        </div>
                    </div>                     
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Eventos
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                <p>Em breve.</p>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>             
    </div>
</div>

<!-- Modal -->
<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Exclusão de Jogos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja apagar o jogo?</p>
                <p><strong>Atenção, esta ação é irreversível!</strong></p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="DeleteForever" type="button" class="btn btn-primary">Excluir</button>

                <input id="IdJogo" name="IdJogo" type="hidden" value="">

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="confirm-delete-atilheiro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Exclusão de Artilheiro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja apagar o artilheiro?</p>
                <p><strong>Atenção, esta ação é irreversível!</strong></p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="DeleteForeverArtilheiro" type="button" class="btn btn-primary">Excluir</button>

                <input id="IdArtilheiro" name="IdArtilheiro" type="hidden" value="">

            </div>
        </div>
    </div>
</div>