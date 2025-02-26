<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Relatorios_model extends CI_Model {
	
    private $estudantes = 'tb_atletas';
	private $usuarios = 'tb_usuarios';
	private $igrejas = 'tb_igrejas';
	private $cidade = 'tb_cidade';
	private $estado = 'tb_estado';
	private $tipo = 'tb_tipo';
	private $modulo = 'tb_modulo';
	private $exames = 'tb_provas';
	private $provas = 'tb_provas_choices';
    private $boletos = 'tb_boletos';
	private $kardex = 'tb_kardex';
    private $estudantes_boletos = 'tb_atletas_boletos';
	
    public function __construct()
	{
        parent::__construct();
    } 

	/*
	 *  Busca os dados para o relatório
	 */
    public function get_boletos($Data)
	{
		$tbTabelas = '';
		
		// FILTRO da SITUAÇÃO NÃO QUITADO
		if($Data['Situacao'] == 1)
		{
			$tbTabelas = $this->boletos . ', ' . $this->estudantes_boletos . ', ' . $this->estudantes . ', ' . $this->igrejas;
			
			$this->db->select('tb_atletas.Nome, tb_boletos.DataDocumento, tb_boletos.DataVencimento AS Operacao, tb_boletos.NossoNumero, tb_boletos.ValorBoleto');
			$this->db->where('tb_boletos.Pago', 0);
			$this->db->order_by('tb_boletos.DataVencimento', 'ASC');
		}

		// FILTRO da SITUAÇÃO QUITADO
		if($Data['Situacao'] == 2)
		{
			$tbTabelas = $this->boletos . ', ' . $this->estudantes_boletos . ', ' . $this->estudantes . ', ' . $this->kardex . ', ' . $this->igrejas;
			
			$this->db->select('tb_atletas.Nome, tb_boletos.DataDocumento, tb_kardex.dtOperacao AS Operacao, tb_boletos.NossoNumero, tb_boletos.ValorBoleto');
			$this->db->where('tb_boletos.Id = tb_kardex.idBoleto');
			$this->db->where('tb_boletos.Pago', 1);
			$this->db->order_by('tb_kardex.dtOperacao', 'ASC');
		}
		
		// FILTRO com igreja
		if($Data['IdIgreja'] > 0)
		{
			$this->db->where('tb_atletas.IdIgreja', $Data['IdIgreja']);
		}
		
		// FILTRO das datas de inicio e fim
		if($Data['Inicio'] != "" && $Data['Fim'] != "")
		{
			if($Data['Situacao'] == 1)
			{
				$this->db->where('tb_boletos.DataVencimento >=', data_br_to_us($Data['Inicio']));
				$this->db->where('tb_boletos.DataVencimento <=', data_br_to_us($Data['Fim']));
			}
			
			if($Data['Situacao'] == 2)
			{
				$this->db->where('tb_kardex.dtOperacao >=', data_br_to_us($Data['Inicio']));
				$this->db->where('tb_kardex.dtOperacao <=', data_br_to_us($Data['Fim']));
			}
		}
					  
		// EXECUTA a query
		$query = $this->db
					  ->where('tb_boletos.Id = tb_atletas_boletos.IdBoletoBancario')
					  ->where('tb_atletas_boletos.IdEstudante = tb_atletas.Id')
					  ->where('tb_atletas.IdIgreja = tb_igrejas.Id')
					  ->get($tbTabelas);

		if($query->num_rows() > 0)
		{
			return $query->result();
        }
		else
		{
			return 0;
		}
    }

	/*
	 *  Busca os dados para o relatório
	 */
    public function get_alunos($IdIgreja)
	{
		$query = $this->db
					  ->select('tb_atletas.UsuarioId, tb_atletas.Nome')
					  ->where('tb_atletas.IdIgreja', $IdIgreja)
					  ->order_by('tb_atletas.Nome', 'ASC')
					  ->get($this->estudantes);
        
		if($query->num_rows() > 0)
		{
			return $query->result();
        }
		else
		{
			return array();
		}
    }

	/*
	 *  Busca os dados para o relatório
	 */
    public function get_notas($IdAluno)
	{
		$query = $this->db
					  ->select('quiz_id, int_acerto')
					  ->where('id_usuario', $IdAluno)
					  ->where('int_encerrado', 1)
					  ->get($this->provas);
        
		if($query->num_rows() > 0)
		{
			return $query->result();
        }
		else
		{
			return 0;	
		}
    }
	
	/*
	 *  Conta os alunos por igreja
	 */	
	public function count_alunos($IdIgreja)
	{
		$query = $this->db
					  ->select('COUNT(Id) AS num_Alunos')
					  ->where('IdIgreja', $IdIgreja)
					  ->get($this->estudantes);
        
		return $query->row()->num_Alunos;
	}
	
	/*
	 *  Verifica quantos fizeram a prova
	 */	
	public function count_alunos_in($IdIgreja)
	{
		$query = $this->db
					  ->select('DISTINCT(tb_atletas.Nome) AS Nome')
					  ->where('tb_atletas.UsuarioId = tb_provas_choices.id_usuario')
					  ->where('tb_atletas.IdIgreja = tb_igrejas.Id')
					  ->where('tb_igrejas.Id', $IdIgreja)
					  ->where('tb_provas_choices.int_encerrado', 1)
					  ->get($this->provas . ', ' . $this->igrejas . ', ' . $this->estudantes);

		if($query->num_rows() > 0)
		{
			return $query->num_rows();
        }
		else
		{
			return 0;	
		}
	}
	
	/*
	 *  Traz a soma das notas
	 */	
	public function get_sum_nota($id_prova, $id_igreja)
	{
		$query = $this->db
					  ->select_sum('tb_provas_choices.int_acerto')
					  ->where('tb_atletas.UsuarioId = tb_provas_choices.id_usuario')
					  ->where('tb_atletas.IdIgreja = tb_igrejas.Id')
					  ->where('tb_igrejas.Id', $id_igreja)
					  ->where('tb_provas_choices.quiz_id', $id_prova)
					  ->where('tb_provas_choices.int_encerrado', 1)
					  ->get($this->provas . ', ' . $this->igrejas . ', ' . $this->estudantes);		
	
		return $query->row()->int_acerto;
	}

	/*
	 *  Verifica quantos não fizeram a prova
	 */	
	public function count_alunos_with_in($IdIgreja)
	{
		$query = $this->db
					  ->select('COUNT(Id) AS num_Alunos')
					  ->where('IdIgreja', $IdIgreja)
					  ->where('UsuarioId NOT IN (SELECT id_usuario FROM tb_provas_choices WHERE int_encerrado = 1)', NULL, FALSE)
					  ->get($this->estudantes);
        
		return $query->row()->num_Alunos;
	}

    // Retorna o nome da igreja em que a abertura foi feita
    public function getChurch($idIgreja)
	{
       return $this->db
                   ->where('Id', $idIgreja)
				   ->where('Excluido', 0)
                   ->get($this->igrejas)
                   ->row()
                   ->Nome;                       
    }

	/*
	 *  Busca os dados para o relatório
	 */
    public function get_filiais($IdIgreja)
	{
		if($IdIgreja > 0) $this->db->where('Id', $IdIgreja);
		
		$query = $this->db
					  ->select('Id, Nome')
					  ->where('Id !=', 0)
					  ->where('Excluido', 0)
					  ->order_by('Nome', 'ASC')
					  ->get($this->igrejas);
        
		if($query->num_rows() > 0)
		{
			return $query->result();
        }
    }
	
	/*
	 *  Busca os dados para o relatório
	 */
    public function get_provas()
	{
		$query = $this->db
					  ->select('id, str_quiz')
					  ->where('int_status', 1)
					  ->order_by('id', 'ASC')
					  ->get($this->exames);
        
		if($query->num_rows() > 0)
		{
			return $query->result();
        }
		else
		{
			return 0;
		}
    }
	
	/*
	 *  Busca a quantidade de alunos naquela determinada igreja
	 */
    public function get_quantidade($id_prova, $id_igreja)
	{
		$query = $this->db
					  ->select('tb_atletas.UsuarioId')
					  ->where('tb_atletas.UsuarioId = tb_provas_choices.id_usuario')
					  ->where('tb_atletas.IdIgreja = tb_igrejas.Id')
					  ->where('tb_igrejas.Id', $id_igreja)
					  ->where('tb_provas_choices.quiz_id', $id_prova)
					  ->where('tb_provas_choices.int_encerrado', 1)
					  ->get($this->provas . ', ' . $this->igrejas . ', ' . $this->estudantes);
        
		if($query->num_rows() > 0)
		{
			return $query->num_rows();
        }
		else
		{
			return 0;
		}
    }

	/*
	 *  Busca a maior notas de alunos naquela determinada igreja
	 */
    public function get_max_nota($id_prova, $id_igreja)
	{
		$query = $this->db
					  ->select_max('tb_provas_choices.int_acerto')
					  ->where('tb_atletas.UsuarioId = tb_provas_choices.id_usuario')
					  ->where('tb_atletas.IdIgreja = tb_igrejas.Id')
					  ->where('tb_igrejas.Id', $id_igreja)
					  ->where('tb_provas_choices.quiz_id', $id_prova)
					  ->where('tb_provas_choices.int_encerrado', 1)
					  ->get($this->provas . ', ' . $this->igrejas . ', ' . $this->estudantes);
        
		if($query->num_rows() > 0)
		{
			return ($query->row()->int_acerto == NULL)? 0 : $query->row()->int_acerto;
        }
    }
	
	/*
	 *  Retorna ID e Nome para Select List
	 */
    public function get_igrejas_dropdown_search()
	{
        $query = $this->db
					  ->select('Id, Nome')
					  ->where('Id !=', 0)
					  ->where('Excluido', 0)
					  ->order_by('Nome', 'ASC')
					  ->get($this->igrejas);
        
        if($query->num_rows() > 0)
		{
			$row[0] = "SELECIONE A FILIAL PARA FILTRAR";
			
			foreach ($query->result() as $aRow)
			{
				$row[$aRow->Id] = 'BDN ' . deepHigher($aRow->Nome);
			}
			
			return $row; 
        }
		else
		{
            return array();
        }
    }
}

?>