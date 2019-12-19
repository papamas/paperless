<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importexcel extends MY_Controller {

	function __construct() {		
		parent::__construct();
		$this->load->library(array('Auth','Menu','form_validation'));
        $this->load->model("agenda/magenda");
	}

	public function index() {

        $target_dir  	= './agenda/';
		$agenda_id 		= $this->input->post('input_agendaid');
		 
		$config = array(
			'upload_path'   => $target_dir,
			'allowed_types' => 'xlsx|xls|csv'      
		);
		
		$this->load->library('upload', $config);
		
		if ($this->upload->do_upload('xls_ins')) {
			$data = $this->upload->data();
			
			chmod($data['full_path'], 0777);
			$this->load->library('Spreadsheet_Excel_Reader');
			$this->spreadsheet_excel_reader->setOutputEncoding('CP1251');

			$this->spreadsheet_excel_reader->read($data['full_path']);
			$sheets 		= $this->spreadsheet_excel_reader->sheets[0];

			$data_excel 	= array();			
			for ($i = 2; $i <= $sheets['numRows']; $i++) {
				if ($sheets['cells'][$i][1] == '') break;
				$data_excel[$i - 1]['agenda_id']    = $agenda_id;
				$data_excel[$i - 1]['nip']   = $sheets['cells'][$i][1];
			    $data_excel[$i - 1]['nomi_periode']   = '201902';
			}
			
			$db_debug 			= $this->db->db_debug; 
			$this->db->db_debug = FALSE; 
			
			if (!$this->magenda->minput_nominatif($data_excel)) {
				$error = $this->db->_error_message(); 
				
			    if(!empty($error))
				{
					$this->session->set_flashdata('gagal', $error);
                }
				else
				{
					$this->session->set_flashdata('berhasil', 'Sukses Import Nominatif');
				}
				
			}
			
			$this->db->db_debug = $db_debug; //restore setting
			

			unlink($data['full_path']);			
			redirect('agenda/nominatif/'.$agenda_id);
		}
		else
		{
			$this->session->set_flashdata('gagal', 'Gagal Import Nominatif');
			redirect('agenda/nominatif/'.$agenda_id);
		}
	}

}
