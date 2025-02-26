<?php 

echo $AddCss;
echo $AddJavascript;

$attributes = array('id' => 'FormV', 'name' => 'FormV');

echo form_open(base_url() . '', $attributes); 

?>

<div class="jumbotron">
    <div class="col-sm-12">
	
        <?php
        
        if(isset($Mensagem)) echo '<div class="alert alert-danger mb-5" role="alert">' . $Alerta . '</div>';

        $ValorPendente = 0.00;
        
        if(is_object($boletosAbertos))
        {
            $PreviousYear = date("Y") - 1;
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
                        </tr>';

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
                                    $status = 'Parcela pendente que será incluída nos pagamentos de '.date('Y').'.';
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
                        </tr>';

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
            
        <h5>Pagamento(s) de Boleto Bancário - <?php echo date('Y'); ?></h5>

        <?php if(isset($Bloqueados)) echo '<div class="alert alert-danger mt-3 mb-3" role="alert">' . $Bloqueados . '</div>'; else echo '<div class="alert alert-primary mt-3 mb-3" role="alert">Caro aluno, ao clicar no boleto para pagar adicionaremos 15 dias em sua data de vencimento :)</div>' ?>

        <table class="table table-striped table-dark table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Mês</th>
            <th scope="col">Vencimento</th>
            <th scope="col">Valor</th>
            <th scope="col">Status</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
            
        <?php

        $Quitados = 0;
        
        foreach($boletosAtuais as $arboletosAtuais): 

        ?>

        <tr>
            <td><?php echo show_month($arboletosAtuais->Mes); ?></td>
            <td><div id="venc_<?php echo $arboletosAtuais->IdBoletoBancario; ?>"><?php echo data_us_to_br($arboletosAtuais->DataVencimento); ?></div></td>
            <td><?php echo num_to_user($arboletosAtuais->ValorBoleto); ?></td>
            <td><?php echo ($arboletosAtuais->Pago == 1)? 'Pago' : 'Aberto'; ?></td>
            <td>
                <?php 

                    if($arboletosAtuais->Pago == 0 && $arboletosAtuais->Remessa == 0 && $arboletosAtuais->Enviado == 0)
                    { 
                        echo '<div id="wish_'.$arboletosAtuais->IdBoletoBancario.'">
                                <a href="javascript:Registro('.$arboletosAtuais->IdBoletoBancario.')" class="text-light">Pagar boleto</a>
                            </div>';
                    }

                    if($arboletosAtuais->Pago == 0 && $arboletosAtuais->Remessa == 1 && $arboletosAtuais->Enviado == 0)
                    { 
                        echo 'Aguarde 24 horas para pagar :)';
                    }
                    
                    if($arboletosAtuais->Pago == 0 && $arboletosAtuais->Remessa == 1 && $arboletosAtuais->Enviado == 1)
                    { 
                        echo '<a href="'.base_url().'boletos/visualizar/'.$arboletosAtuais->IdBoletoBancario.'" target="_blank" class="text-light">Visualizar boleto</a>';
                    }

                ?>
            </td>
        </tr>

        <?php 

            $Quitados += $arboletosAtuais->Pago;

        endforeach; 
        
        ?>
        
        <?php if($Quitados == 0){ ?>
        
            <tr>
                <td colspan="5">
                    <button id="clique_reversao" type="button" class="btn btn-success">Desejo trocar forma de pagamento</button>
                    <input type="hidden" id="SelectOrigem" name="SelectOrigem" value="1">
                </td>
            </tr>

        <?php } ?>

        </tbody>    
        </table><br />
    
    </div>
</div>

<?php echo form_close(); ?>