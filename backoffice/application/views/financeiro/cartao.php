<script src="https://ecommerce.granitopagamentos.com.br/js/paymentmethodnonce.min.js" type="text/javascript"></script>

<?php

echo $AddJavascript;
echo $AddCss;

$attributes = array('id' => 'FormV', 'name' => 'FormV');

echo form_open(base_url() . '', $attributes); 

?>

<div class="ajax" style="padding-top: 20%">
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-light" style="width: 5rem; height: 5rem;" role="status">
            <span class="sr-only">Finalizando a operação...</span>
        </div>
    </div>
</div>

<div class="jumbotron">
    <div class="col-sm-12">

        <?php

        if(isset($Mensagem)) echo $Mensagem;
        
        $ValorPendente = 0.00;
        
        if(is_object($boletosAbertos))
        {
            $PreviousYear = date('Y') - 1;
            $status = '';
            
            if($boletosAbertos->tipo == 'boleto')
            {
                $Body = '<h5>Débito(s) pendentes do ano anterior (Boletos)</h5>

                        <table class="table table-striped table-dark table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Mês</th>
                            <th scope="col">Vencimento</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>';

                        $Mes = 1;
                        
                        foreach($boletosAbertos as $arboletosAbertos): 
                            
                            if($arboletosAbertos == 'boleto') continue;
                            
                            if($arboletosAbertos->Pago == 1)
                            {
                                $status = '<strong>Boleto pago.</strong>';
                            }
                            else
                            {
                                if($arboletosAbertos->Ajuste == 0)
                                {
                                    $status = 'Pendência que será incluída nos pagamentos de '.date('Y').'.';
                                }
                                else
                                {
                                    $status = 'Parcela de ajuste financeiro que será incluída nos pagamentos de '.date('Y').'.';
                                }
                            }

                            $MesFinal = ($arboletosAbertos->MesBoleto == '')? $Mes : $arboletosAbertos->Mes;
                            
                            $Body .= '<tr>
                                        <td>'.show_month($MesFinal).'</td>
                                        <td>'.data_us_to_br($arboletosAbertos->DataVencimento).'</td>
                                        <td>R$ '.num_to_user($arboletosAbertos->ValorBoleto).'</td>
                                        <td>'.$status.'</td>
                                    </tr>';

                            $ValorPendente += $arboletosAbertos->ValorBoleto;

                            $Mes++;

                        endforeach; 

                echo $Body .= '</tbody></table><br /><br />';
            }
            else
            {
                $Body = '<h5>Débito(s) Pendentes (Cartão de Crédito)</h5>

                        <table class="table table-striped table-dark table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Numero do Pedido</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Valor Total</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>';

                        foreach($boletosAbertos as $arboletosAbertos): 

                            if($arboletosAbertos == 'cartao') continue;
                            
                            if($arboletosAbertos->payment_status == 0)
                            {
                                $Status = '<strong>Pendente</strong>';	
                            }
                            if($arboletosAbertos->payment_status == 1)
                            {
                                $Status = '<strong>Aguardando aprovação</strong>';		
                            }
                            if($arboletosAbertos->payment_status == 2)
                            {
                                $Status = '<strong>Pago</strong>';		
                            }
                            if($arboletosAbertos->payment_status == 3)
                            {
                                $Status = '<strong>Negado</strong>';		
                            }
                            if($arboletosAbertos->payment_status == 4)
                            {
                                $Status = '<strong>Negado</strong>';		
                            }        
                            if($arboletosAbertos->payment_status == 5)
                            {
                                $Status = '<strong>Cancelado</strong>';		
                            }
                            if($arboletosAbertos->payment_status == 6 || $arboletosAbertos->payment_status == 99)
                            {
                                $Status = '<strong>Não finalizado</strong>';		
                            }
                            if($arboletosAbertos->payment_status == 7)
                            {
                                $Status = '<strong>Autorizado</strong>';		
                            }

                            $Body .= '<tr>
                                        <td>'.inclui_zero_esq($arboletosAbertos->pedido).'</td>
                                        <td>Cartão de Crédito</td>
                                        <td>R$ '.num_to_user($arboletosAbertos->valor).'</td>
                                        <td>'.$Status.'</td>
                                    </tr>';

                            $ValorPendente += $arboletosAbertos->valor;

                        endforeach; 

                echo $Body .= '</tbody></table><br />';
            }
        }
        
        ?>    
        
        <h5>Pagamento somente em Cartão de Crédito referente ao ano letivo de <?php echo date('Y'); ?></h5>

        <table class="table table-striped table-dark table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Numero do Pedido</th>
            <th scope="col">Valor Total</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>        
            
        <?php 
        
        $Status = '';
        $ValorAtual = 0.00;
        
        foreach($boletosAtuais as $arboletosAtuais): 
        
            $ValorAtual = $arboletosAtuais->valor;
            
            if($arboletosAtuais->payment_status == 0)
            {
                $Status = '<strong>Pendente</strong>';	
            }
            if($arboletosAtuais->payment_status == 1)
            {
                $Status = '<strong>Negado</strong>';		
            }
            if($arboletosAtuais->payment_status == 2)
            {
                $Status = '<strong>Pago</strong>';		
            }
            if($arboletosAtuais->payment_status == 3)
            {
                $Status = '<strong>Negado</strong>';		
            }
            if($arboletosAtuais->payment_status == 4)
            {
                $Status = '<strong>Negado</strong>';		
            }        
            if($arboletosAtuais->payment_status == 5)
            {
                $Status = '<strong>Cancelado</strong>';		
            }
            if($arboletosAtuais->payment_status == 6 || $arboletosAtuais->payment_status == 99)
            {
                $Status = '<strong>Não finalizado</strong>';		
            }
            if($arboletosAtuais->payment_status == 7)
            {
                $Status = '<strong>Autorizado</strong>';		
            }
            
            $ValorCredit     =  num_to_user($ValorAtual);
            $payment_status  =  $arboletosAtuais->payment_status;
            $payment_access  =  $arboletosAtuais->payment_access;
            $pedido_cartao   =  inclui_zero_esq($arboletosAtuais->pedido);
            
        ?>
            
        <tr>
            <td><?php echo $pedido_cartao; ?></td>
            <td>R$ <?php echo $ValorCredit; ?></td>
            <td><?php echo $Status; ?></td>
        </tr>
        
        <?php 
        
        endforeach; 
        
        ?>
        
        <?php if($payment_status == 0 || $payment_status == 3 || $payment_status == 4 || $payment_status == 5 || $payment_status == 6 || $payment_status == 99){ ?>
        
            <tr>
                <td colspan="3"><button id="clique_reversao" type="button" class="btn btn-success">Desejo trocar forma de pagamento</button></td>
            </tr>
        
        <?php } ?>

        </tbody>
        </table>

        <?php if($payment_status == 0 || $payment_status == 1 || $payment_status == 3 || $payment_status == 4 || $payment_status == 5 || $payment_status == 6 || $payment_status == 99){ ?>

                <!-- Button trigger modal -->
                
                <button id="clique_granito" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalCenter">
                    Clique para pagar
                </button> 
                
                <!-- GRANITO -->
                <input type="hidden" id="order_number" name="order_number" value="<?php echo $pedido_cartao; ?>"> 
                <input type="hidden" id="cart_name" name="cart_name" value="Instituto Global - Matricula ano letivo de <?php echo date('Y'); ?>">  
                <input type="hidden" id="cart_unitprice" name="cart_unitprice" value="<?php echo str_replace('.', '', $ValorAtual); ?>">  

                <input type="hidden" id="customer_name" name="customer_name" value="<?php echo $this->session->userdata('Nome'); ?>"> 
                <input type="hidden" id="customer_email" name="customer_email" value="<?php echo trim($this->session->userdata('Email')); ?>"> 
                <input type="hidden" id="customer_identity" name="customer_identity" value="<?php echo $this->session->userdata('CPF'); ?>">
                <input type="hidden" id="SelectOrigem" name="SelectOrigem" value="2">                

        <?php } ?>
        
        <?php echo form_close(); ?>
        
    </div>
</div>        

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white bg-dark" id="exampleModalCenterTitle">Finalizar pagamento do curso Global</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="novo_order_number" name="novo_order_number" value="" /> 

                <div class="form-group">
                    
                    <label for="installments" class="col-sm-2 col-form-label"><strong>PARCELAS</strong></label>
                    
                    <div class="col-sm-6">

                        <select id="installments" name="installments" class="form-control">
                        <option value="" selected="select">Selecione quantidade de parcelas</option>
                            <?php

                            $Legenda  =  '';
                            $Select   =  '';
                            
                            for($x = 1; $x <= 12; $x++)
                            {
                                if($x == 1)
                                {
                                    $Legenda = 'Pagamento à vista de R$ ' . num_to_user($ValorAtual);
                                }
                                else
                                {
                                    $Legenda = $x . ' parcelas sem juros de R$ ' . num_to_user($ValorAtual / $x);
                                }

                                $Select .= '<option value="'.$x.'">'.$Legenda.'</option>';
                            }

                            echo $Select;

                            ?>
                        </select>

                    </div>
                </div>

                <iframe id="pago_iframe" src="about:blank" style="border:none; height: 490px; width: 100%; padding-top:8px;padding-left:14px"></iframe>  
                
            </div>
            <div class="modal-footer">
                <button id="clique_fechamento" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="clique_pagamento" type="button" class="btn btn-dark">Clique para pagar</button>
            </div>
        </div>
    </div>
</div>