<?php

    echo $AddCss;
    echo $AddJavascripts;
	
?>

<div class="jumbotron">
    <div class="col-sm-12">   

        <h5>Tela de pagamento da mensalidade trimestral do ano de <?php echo date('Y'); ?>.</h5>

        <hr />

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>atletas">Atletas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pagar Mensalidade</li>
            </ol>
        </nav>          
        
        <fieldset>

            <div class="alert alert-dark" role="alert"><strong>Dados do Atleta</strong></div>

            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-dark" role="alert"><strong><?php echo mb_strtoupper($AddAtleta->Nome); ?></strong></div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-dark" role="alert"><strong><?php echo mb_strtolower($AddAtleta->Email); ?></strong></div>
                </div>
            </div>

            <?php if($AddAtleta->Posicao == 'GOLEIRO'){ ?>

                    <div class="alert alert-warning" role="alert"><strong>GOLEIRO é isento de pagamento.</strong></div>

            <?php }else{ ?>

                    <div class="alert alert-warning" role="alert"><strong>Valor da contribuição esse ano será de R$ <?php echo num_to_user(VALOR_TRIMESTRAL); ?> por trimestre.</strong></div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <h5 class="card-title">Pagamentos do Ano</h5>
                                    
                                    <?php 
            
                                        $attributes = array('id' => 'FormA', 'name' => 'FormA');

                                        echo form_open(base_url(), $attributes); 

                                    ?>	
                                    
                                    <table class="table table-striped table-dark table-responsive-sm">
                                    <thead>
                                    <tr>
                                        <th scope="col">Período</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Status</th>
                                    </tr>

                                    <?php foreach($AddPagamento as $sPagamento): ?> 
                                    
                                    <tr>
                                        <td><?php echo $sPagamento->Periodo; ?></td>
                                        <td>R$ <?php echo num_to_user($sPagamento->Valor); ?></td>
                                        <td>
                                            <?php

                                                $js = 'id="Status_' . $sPagamento->Id . '" class="form-control"';

                                                echo form_dropdown('Status[]', array(0 => 'Pendente', 1 => 'Quitado'), set_value('Status[]', $sPagamento->Pago), $js);                            

                                            ?>
                                        </td>
                                    </tr>
                                    
                                    <?php endforeach; ?>
                                    
                                    </tbody>
                                    </table>

                                    <div id="Success"></div>
                                    
                                    <input id="IdAtleta" name="IdAtleta" type="hidden" value="<?php echo $AddAtleta->Id; ?>" />
                                    
                                    <?php echo form_close(); ?>

                                </div>
                            </div>
                        </div>
                    </div>

            <?php } ?>

        </fieldset>

    </div>
</div>