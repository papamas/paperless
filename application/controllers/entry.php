<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry extends MY_Controller {
	
	var $menu_id    = 17;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt'));
		$this->load->model('entry/entry_model', 'entry');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	public function index()
	{
			
		$data['menu']     		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		$data['layanan']  		= $this->entry->getPelayanan();
		$data['instansi']  		= $this->entry->getInstansi();
		$data['show']  			= FALSE;	
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('entry/index',$data);
	}
	
	public function sapk()
	{			
		$data['menu']     =  $this->menu->build_menu();	
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();	
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('entry/sapk',$data);
	}
	
	
	public function getEntry()
	{
		
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim');
		$this->form_validation->set_rules('reportrange', 'Periode', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('perintah', 'Perintah', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'trim');
		
		$search           = $this->input->post();
		$perintah         = $this->input->post('perintah');		
				
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']     		=  $this->menu->build_menu();	
			$data['lname']    		=  $this->auth->getLastName();        
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['layanan']  		= $this->entry->getPelayanan();
			$data['instansi']  		= $this->entry->getInstansi();
			$data['show']  			= FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('entry/index',$data);
		
		}
		else
		{	
			$q	  			  = $this->entry->getUsulDokumen($search);
			
			if($perintah == 1)
			{
				
				$data['menu']     	=  $this->menu->build_menu();		
				$data['lname']    	=  $this->auth->getLastName();        
				$data['name']     	=  $this->auth->getName();
				$data['jabatan']  	=  $this->auth->getJabatan();
				$data['member']	  	=  $this->auth->getCreated();
				$data['avatar']	  	=  $this->auth->getAvatar();
				
				$data['layanan']  	= $this->entry->getPelayanan();
				$data['instansi']   = $this->entry->getInstansi();
				$data['show']  		= TRUE;
				$data['usul']	  	=  $q;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('entry/index',$data);
			}
			else
			{	
				$this->_getExcel($q);
			}

		}
		
	}
	
	
	private function _getExcel($q)
	{
		
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "ENTRY BERKAS ".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'ENTRY BERKAS STATUS ACC';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';		
		$html .= '<tr><td  colspan=2>INSTANSI</td><td>'.$row->instansi.'</td></tr>';		
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NO</th>
					<th>NIP</th>
					<th>NAMA</th>
					<th>NO AGENDA</th>
					<th>TANGGAL</th>
					<th>PELAYANAN</th>
					<th>STATUS</th>
					<th>TANGGAL</th>
					<th>PERSETUJUAN</th>
					<th>TANGGAL PERSETUJUAN</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->nama}</td>";	
				$html .= "<td>{$r->agenda_nousul}</td>";	
				$html .= "<td>{$r->agenda_timestamp}</td>";
				$html .= "<td>{$r->layanan_nama}</td>";	
				$html .= "<td>{$r->nomi_status}</td>";
				$html .= "<td>{$r->verify_date}</td>";
				$html .= "<td>{$r->nomi_persetujuan}</td>";
				$html .= "<td>{$r->tgl}</td>";
				$html .= "</tr>";
				$i++;
			}
			$html .="</table>";
			echo $html;
		}else{
			$html .="<tr><td  colspan=6 >There is no data found</td></tr></table>";
			echo $html;
		} 	
	}	
	
	public function simpan()
	{
		$data['agenda'] 			= $this->myencrypt->decode($this->input->post('agenda'));
		$data['nip']       			= $this->myencrypt->decode($this->input->post('nip'));
		$data['persetujuan']		= $this->input->post('persetujuan');
		$data['tanggal']			= $this->input->post('tanggal');
		
		$this->form_validation->set_rules('persetujuan', 'Persetujuan', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$db_debug 			= $this->db->db_debug; 
			$this->db->db_debug = FALSE; 
			if (!$this->entry->simpanPersetujuan($data))
			{
				$error 				= $this->db->_error_message(); 
				$data['pesan']		= $error;
				if(!empty($error))
				{
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
					return FALSE;	
				}				
			}
			else
			{
				$data['pesan']	= "Sukses Entry Persetujuan";
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}

			$this->db->db_debug = $db_debug; //restore setting		
		}
		
    }	
	
	public function simpanTahapan()
	{
		$data['agenda'] 			= $this->myencrypt->decode($this->input->get('agenda'));
		$data['nip']       			= $this->myencrypt->decode($this->input->get('nip'));
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		if (!$this->entry->simpanTahapan($data))
		{
			$error 				= $this->db->_error_message(); 
			$data['pesan']		= $error;
			if(!empty($error))
			{
				$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
				return FALSE;	
			}				
		}
		else
		{
			$data['pesan']	= "update tahapan proses cetak";
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
		}

		$this->db->db_debug = $db_debug; //restore setting
	
	}	
}
