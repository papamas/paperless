<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pupns extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();
		$this->load->library(array('Auth','Menu','form_validation'));	
	    $this->load->model('pupns/pupns_model', 'pupns');
	} 
	
	public function index()
	{
		$data['menu']     =  $this->menu->build_menu();
		$data['pesan']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		
		$data['instansi']   = $this->pupns->getInstansi();
		$data['golongan']	= $this->pupns->getGolru();

		
		$this->load->view('pupns/index',$data);
		
	}
	
	public function salin()
	{
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('statusKepegawaian', 'statusKepegawaian', 'required');
		$this->form_validation->set_rules('TglLahir', 'TglLahir', 'required');
	    $this->form_validation->set_rules('golAwal', 'Golongan Awal', 'required');
		$this->form_validation->set_rules('golAkhir', 'Golong Akhir', 'required');
		$this->form_validation->set_rules('instansiInduk', 'Instansi Induk', 'required');
		$this->form_validation->set_rules('instansiKerja', 'Instansi Kerja', 'required');
		$this->form_validation->set_rules('kanreg', 'Kanreg', 'required');
			
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']    = '';
		}
		else
		{	
			$nip      			= $this->input->post('nip');
			
			$db_debug 			= $this->db->db_debug; 
		    $this->db->db_debug = FALSE; 	
			
			// cek pada mirror apa sdh ada
			$cekPupns 			= $this->pupns->cekPupns();
						
			if($cekPupns->num_rows() > 0)
			{
				if (!$this->pupns->updatePupns())
				{
					$error = $this->db->_error_message(); 			
					if(!empty($error))
					{
						$data['response']		= FALSE;
						$data['pesan']			= '<div class="box box-warning"><div class="callout callout-warning">
									<h4>'.$error.'!</h4></div></div>';						
					}						
				}
				else
				{
					$data['response']		= TRUE;
					$data['pesan']			= '<div class="box box-success"><div class="callout callout-success">
									<h4>Berhasil Melakukan Update PUPNS!</h4></div></div>';						
				}	
			}
			else
			{					
				if (!$this->pupns->insertPupns()) {
					$error = $this->db->error();  			
					if(!empty($error['message']))
					{
						$data['response']		= FALSE;
						$data['pesan']			= '<div class="box box-warning"><div class="callout callout-warning">
									<h4>'.$error['message'].'!</h4></div></div>';		
						
					}						
				}
				else
				{
					$data['response']		= TRUE;
					$data['pesan']			= '<div class="box box-success"><div class="callout callout-success">
									<h4>Berhasil Menambahkan data PUPNS!</h4></div></div>';  
						
							
				}
			
			}
			$this->db->db_debug = $db_debug; //restore setting			
			
	    }
		
		$data['menu']     =  $this->menu->build_menu();		
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		
		$data['instansi']   = $this->pupns->getInstansi();
		$data['golongan']	= $this->pupns->getGolru();

		
		$this->load->view('pupns/index',$data);
	}	
	
	public function getPns()
	{
	    $query= $this->pupns->getPns();
	    echo json_encode(array('result' => $query->row()));
	}	
	
	public function getPnsdata()
	{
		$query= $this->pupns->getPnsdata();
	    echo json_encode($query->row());
	}	
}
