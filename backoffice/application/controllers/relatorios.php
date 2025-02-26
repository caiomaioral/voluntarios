<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relatorios extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();

        $this->load->model('provas_model');
		$this->load->model('relatorios_model');
    }
	 
	/*
	 *  Lista as familias de acordo com a estudantes logada
	 */
    public function index()
    {
		redirect(base_url() . 'home', 'refresh');
    }

	/*
	 *  Evento para gerar o relatorio que mostra o numero de acertos dos caras
	 */	
	public function resultado_prova()
	{
		$this->load->helper('path');
		$this->load->library('fpdf');
		
		// Posição vertical inicial
		$this->fpdf->SetY('-1');
		$this->fpdf->SetMargins(8, 8, 8);
		
		// Texto do título
		$titulo = utf8_decode('Resultados da Prova');

		$this->fpdf->SetTitle($titulo);
		$this->fpdf->Cabecalho($titulo);

		$this->fpdf->ln(8);
		
		$id_usuario  =  $this->input->post('id_usuario');
		$id_quiz     =  $this->input->post('id_quiz');
		
		// Carrega os dados no BD
		$Quiz = $this->provas_model->get_quiz($id_quiz, $id_usuario);
		
		$Quiz->str_choices = unserialize($Quiz->str_choices);            
            
		$Quiz->questions = $this->provas_model->get_questions(array_keys($Quiz->str_choices));
		
		$this->fpdf->Cell(12, 6, 'ALUNO:', 0, 0, 'L');
		$this->fpdf->Cell(50, 6, $this->session->userdata('Nome'), 0, 0, 'L');	

		$this->fpdf->ln(10);

		$this->fpdf->Cell(20, 6, 'ACERTOS:', 0, 0, 'L');
		$this->fpdf->Cell(50, 6, $Quiz->int_acerto . '%', 0, 0, 'L');		
		
		$this->fpdf->ln(5);

		$this->fpdf->Cell(20, 6, 'TEMPO:', 0, 0, 'L');
		$this->fpdf->Cell(50, 6, $Quiz->time, 0, 0, 'L');		

		$this->fpdf->ln(10);
		
		$x = 1;
		
		foreach ($Quiz->questions as $question):
			
			$this->fpdf->SetFont('Arial', 'B', 7);
			$this->fpdf->Write(4, $x . ' - ' . utf8_decode(trim(strip_tags($question->str_question))));
			$this->fpdf->SetFont('Arial', '', 7);
			
			$this->fpdf->ln(10);

			foreach ($question->answer as $answer):
				
				if($Quiz->str_choices[$question->id] == $answer->id)
				{
					if($Quiz->str_choices[$question->id] == $question->quiz_answers_id)
					{
						//$this->fpdf->SetTextColor(0, 0, 255);
						$this->fpdf->SetTextColor(3, 192, 60);
					}
					else
					{
						$this->fpdf->SetTextColor(255, 0, 0);
					}
				}
				elseif($answer->id === $question->quiz_answers_id)
				{
					$this->fpdf->SetTextColor(3, 192, 60);
				}
				
				$this->fpdf->Write(5, utf8_decode(strip_tags($answer->str_answer)));
				$this->fpdf->SetTextColor(0, 0, 0);
				$this->fpdf->ln(5);
								
			endforeach;	
			
			$this->fpdf->ln(10);		
			
			$x++;
			
		endforeach;
		
		$this->fpdf->Rodape();
		$this->fpdf->Output();
	}	
}