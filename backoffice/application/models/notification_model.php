<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Notification_model extends CI_Model {
	
    private $eventos            =  'tbl_eventos';
    private $inscricao          =  'tbl_inscricao_conferencia';
    private $dependente         =  'tbl_dependentes_conferencia';
    private $cartao             =  'tbl_cartao';
    private $notification       =  'tbl_notification_tmp';   
    private $log_notification   =  'tbl_log_notification';   
	
    public function __construct()
    {
        parent::__construct();
    } 
    
    //
    // Verifica se o Evento esta perto do fim da capacidade
    //    
    public function get_evento_notification()
    {
        //
        // Esvazia a tabela temporária
        //
        $this->db->truncate($this->notification);
        
        $query = $this->db
                      ->select('id_eventos, titulo, limite')
                      ->where('status', 1)
                      ->where('validade >', date('Y-m-d'))
                      ->get($this->eventos);

        if($query->num_rows() > 0)
        {
            $Evento = array();
            
            foreach($query->result() as $aRow)
            {
                //
                // Limite do evento
                //
                $limite = $aRow->limite;

                //
                // Pesquisa o evento para comparação
                //
                $eventoQuery = $this->db
                                    ->select('pedido')    
                                    ->where('id_evento', $aRow->id_eventos)
                                    ->where('payment_status', 2)
                                    ->get($this->cartao); 
                              
                if($eventoQuery->num_rows() > 0)
                {
                    $pagos = $eventoQuery->num_rows();     
                }
                else
                {
                    $pagos = 0;     
                }
                
                if(intval($limite - $pagos) < 50)
                {
                    $Evento['id_evento']  =  $aRow->id_eventos;
                    $Evento['titulo']     =  $aRow->titulo;
                    $Evento['limite']     =  $limite;
                    $Evento['pagos']      =  $pagos;
                    
                    //
                    // Inclui as notificações no banco
                    //
                    $this->db->insert($this->notification, $Evento);
                }
            }   
            
            $Status = 'ROTINA OK';

            echo 'OK';
        }
        else
        {
            $Status = 'ROTINA DEU ERRO';

            echo 'ERRO';
        }

        //
        // Inclui na tabela de Log
        //    
        $this->db->insert($this->log_notification, array('Status' => $Status, 'Data' => date('Y-m-d H:i:s')));       
    }
    
    //
    // Mostra para o usuário se existe ou não notificações
    //    
    public function show_evento_notification()
    {    
        //
        // Verifica por igreja
        //
        if($this->session->userdata('intTipo') <> 0)
        {
            $this->db->where('id_evento', $this->session->userdata('intTipo'));
        }

        //
        // Busca se existe notificações
        //
        $query = $this->db->get($this->notification); 

        //
        // Estrutura do HTML
        //    
        $Body = '';
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $aRow)
            {
                $Body .= '<div class="td_Titulo" style="background-color: #f8d7da; border-color: #f5c6cb;">
                            <table>
                            <tr>
                                <td width="50"><img src="'.base_url().'assets/images/alert_not.png" width="45" height="35"></td>
                                <td>';

                                if($this->session->userdata('intTipo') <> 0)
                                {
                                    $Body .= '<a href="javascript:void(0)" style="font-family: \'Play\'; font-size: 16px; color: #1c1c1c; margin-left: 5px; font-weight: bold">Atenção! O evento '.$aRow->titulo.' já possui '.$aRow->pagos.' inscritos pagos de um total de '.$aRow->limite.'.</a>';
                                }
                                else
                                {
                                    $Body .= '<a href="'.base_url().'eventos/alterar/'.$aRow->id_evento.'" style="font-family: \'Play\'; font-size: 16px; color: #1c1c1c; margin-left: 5px; font-weight: bold">Atenção! O evento '.$aRow->titulo.' já possui '.$aRow->pagos.' inscritos pagos de um total de '.$aRow->limite.'.</a></h3>';
                                }                                

                $Body .= '</td></tr></table></div>';                
            }                          

            return $Body;
        }
        else
        {
            return $Body;
        }
    }
}

?>