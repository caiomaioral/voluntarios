<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Confirmacao extends MY_Controller {
	 
    public function __construct()
	{
        parent::__construct();
		
        $this->load->model('inscricao_model');		
	}

	/*
	 *  Metodo que chama view index
	 */
	public function index()
	{
		if($this->session->flashdata('Body') != '')
		{
		    $this->data['AddJavascript']  	=   load_js(array('scripts', 'cartao'));
			$this->data['Body'] 			=   $this->session->flashdata('Body');
			
			$this->usable('confirmacao');
		}
		else
		{
			redirect(base_url() . 'inscricao');	
		}
	}

	/*
	 *  Metodo que chama view index
	 */
	public function sucesso()
	{
		$this->usable('sucesso');
	}

	/*
     *  Vamos ver o End-Point
     */
    public function endpoint()
    {
        $String_Venda = '';
        
        /*
         *  Dados do FORM
         */        
        $Data['order_number']         =   $this->input->post('order_number');
        $Data['valor']                =   str_replace(',', '', str_replace('.', '', $this->input->post('unitprice')));
        $Data['installment_value']    =   ($this->input->post('installment_value') == '')? NULL : str_replace(',', '', str_replace('.', '', $this->input->post('installment_value')));
        $Data['customer_name']        =   $this->input->post('customer_name');
        $Data['customer_email']       =   $this->input->post('customer_email');
        $Data['customer_identity']    =   $this->input->post('customer_identity');
        $Data['payment_method_type']  =   $this->input->post('payment_method_type');
        
        /*
         *  Captura da Venda Recorrente
         */        
        if($Data['payment_method_type'] == 1)
        {
            $date = date_create(date('Y-m-d'));

            date_add($date, date_interval_create_from_date_string('12 months'));
    
            /*
             *  End date
             */ 
            $Data['end_date']             =   date_format($date, 'Y-m-d');  
            $Data['intervalo_cobranca']   =   'Monthly';  
    
            $String_Venda = ',
                            "RecurrentPayment": {
                                "Interval": "'.$Data['intervalo_cobranca'].'",
                                "EndDate": "'.$Data['end_date'].'"
                            }'; 
        }
        else
        {
            $String_Venda = '';
        }

        /*
         *  Corpo do JSON
         */   
        $data_string = '{
                            "OrderNumber": "'.$Data['order_number'].'",
                            "SoftDescriptor": "1",
                            "Cart": {
                                "Items": [
                                    {
                                        "Name": "Doação para o Instituto Ativar",
                                        "Description": "",
                                        "UnitPrice": '.$Data['valor'].',
                                        "Quantity": 1,
                                        "Type": "Service"
                                    }
                                ]
                            },
                            "Shipping": {
                                "Type": "WithoutShipping"
                            },    
                            "Payment": {
                                "BoletoDiscount": 0,
                                "DebitDiscount": 0
                                '.$String_Venda.'
                            },
                            "Customer": {
                                "Identity": "'.$Data['customer_identity'].'",
                                "FullName": "'.$Data['customer_name'].'",
                                "Email": "'.$Data['customer_email'].'"
                            },
                            "Options": {
                                "AntifraudEnabled": true
                            }
                        }';
        
        $url = 'https://cieloecommerce.cielo.com.br/api/public/v1/orders';                                                             
        
        //  Initiate curl
        $ch = curl_init();
        
         // Set the return transfer
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Set the timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Set the header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                                       'Content-Type: application/json',                                                                                
                                                       'MerchantId: 9d4a2920-e5e9-4837-a748-a31cef116892'
                                                  ));   
        
        // Set the method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                                                                     
        
        // Set the Json
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);        
        
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // Execute
        $result = curl_exec($ch);
        
        // Closing
        curl_close($ch);
        
        // Will dump a beauty json :3
        $CieloReturn = json_decode($result, TRUE);
        
        // Return to JS
        echo $CieloReturn['settings']['checkoutUrl'];
    }		
}