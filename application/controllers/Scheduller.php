<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheduller extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->model('scheduller/scheduller_model', 'scheduller');
		
    }
	
	public function index()
	{
		$query  = $this->scheduller->getNominatif();
		
		
		foreach($query->result() as $value){
			
			$nip      			= $value->nip;
			
			$db_debug 			= $this->db->db_debug; 
		    $this->db->db_debug = FALSE; 	
			
			// cek pada mirror apa sdh ada
			$cekPupns 			= $this->scheduller->cekPupns($nip);
			
			if($cekPupns->num_rows() > 0)
			{
				if (!$this->scheduller->updatePupns($nip))
				{
					$error = $this->db->error(); 			
					if(!empty($error['message']))
					{
						$data['response']		= FALSE;
						$data['pesan']			= $error;						
					}						
				}
				else
				{
					$data['response']		= TRUE;
					$data['pesan']			= "Berhasil Melakukan Update PUPNS!";

					// delete KP
					$this->scheduller->deleteKP($nip);
					
					// get NEW KP Data
					$newKP  = $this->scheduller->getKp_Oracle($nip)->result_array();
					
					//insert new KP
					$this->scheduller->insertKP($newKP);
                    
					
					// delete Pengadaan
					$this->scheduller->deletePengadaan($nip);
					
					// get Pengadaan Data
					$pengadaan  = $this->scheduller->getPengadaan_Oracle($nip)->result_array();
					
					//insert new KP
					$this->scheduller->insertPengadaan($pengadaan);
					
					// delete Pendidikan
					$this->scheduller->deletePendidikan($nip);
					
					// get Pendidikan Data
					$pendidikan  = $this->scheduller->getPendidikan_Oracle($nip)->result_array();
					
					//insert new Pendidikan
					$this->scheduller->insertPendidikan($pendidikan);
					
					//flag
					$this->scheduller->flagNominatif($value->agenda_id,$nip);
				}	
				
			}
			else
			{					
				
				if (!$this->scheduller->insertPupns($nip)) {
					
					$error = $this->db->error();  			
					
					if(!empty($error['message']))
					{
						$data['response']		= FALSE;
						$data['pesan']			= $error['message'];		
						
					}						
				}
				else
				{
					$data['response']		= TRUE;
					$data['pesan']			= 'Berhasil Menambahkan data PUPNS!';						
							
				}
				
			}
			
			
			
			$this->db->db_debug = $db_debug; //restore setting
			
			

		}
		
		echo json_encode($data);
	}
}