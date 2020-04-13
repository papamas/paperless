
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verifikator extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	var $menu_id    = 15;
	var $allow 		= FALSE;

	
	function __construct()
    {
        parent::__construct();
		$this->load->model('verifikator/verifikator_model', 'verifikator');
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation','Telegram'));				
		$this->load->model('menu_model');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
		
	}

	
	public function index()
	{
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$data['menu']     			   =  $this->menu->build_menu();
		$data['name']     			   =  $this->auth->getName();
        $data['jabatan']  			   =  $this->auth->getJabatan();
		$data['member']	  			   =  $this->auth->getCreated();
		$data['avatar']	  			   =  $this->auth->getAvatar();
		$data['show']	  			   = FALSE;
		
		$this->load->view('verifikator/index',$data);
		
	}
	
	public function find()
	{
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->form_validation->set_rules('search', 'search', 'required');
		$this->form_validation->set_rules('searchby', 'searchby', 'required');
		$this->form_validation->set_rules('usul', 'usul', 'trim|required');
		$this->form_validation->set_rules('level', 'level', 'trim');
		
		$data['menu']     				=  $this->menu->build_menu();	
		$data['name']     				=  $this->auth->getName();
        $data['jabatan']  				=  $this->auth->getJabatan();
		$data['member']	  				=  $this->auth->getCreated();
		$data['avatar']	  				=  $this->auth->getAvatar();		
		
		
		if($this->form_validation->run() == FALSE)
		{
			$data['show']	  			   = FALSE;
			$this->load->view('verifikator/index',$data);
		}
        else
		{
 			$usul							=  $this->input->post('usul');
			$search['search']              	=  $this->input->post('search');
			$search['searchby']            	=  $this->input->post('searchby');
			$data['show']	  			    = TRUE;
			
			if($usul != 2)
			{	
				$data['usul']	  				= $this->verifikator->getUsulDokumen($search);
				$this->load->view('verifikator/index',$data);
			}
			else
			{
				$data['usul']	  				= $this->verifikator->getUsulDokumenTaspen();
				$this->load->view('verifikator/indexTaspen',$data);
			}
			
		
		}
		
		
		
	}
	
	public function verifyGet()
	{
		$data['id_agenda'] 	=  $this->myencrypt->decode($this->input->get('i'));
		$data['nip'] 		=  $this->myencrypt->decode($this->input->get('n'));
		$data['layanan_id'] =  $this->myencrypt->decode($this->input->get('p'));
		//$data['tahapan_id'] =  $this->myencrypt->decode($this->input->get('t'));
		
		$this->session->set_userdata($data);
		
		$data['menu']     =  $this->menu->build_menu();	
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['tabs']  	  = $this->verifikator->getAllTab($data['nip']);
		$data['dokumen']  = $this->verifikator->getAllDokumen($data['nip']);
		$data['usul']	  = $this->verifikator->getVerifyUsul($data);
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}		
		$this->load->view('verifikator/verify',$data);
		
	}
	
	
	public function verifyPost()
	{
		
		$data['nip'] 		=  $this->input->post('nip');		
		
		$data['id_agenda']  =  $this->session->userdata('id_agenda');
		$data['layanan_id'] =  $this->session->userdata('layanan_id');
		
		
		$data['menu']     =  $this->menu->build_menu();			
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['tabs']  	  = $this->verifikator->getAllTab($data['nip']);
		$data['dokumen']  = $this->verifikator->getAllDokumen($data['nip']);
		$data['usul']	  = $this->verifikator->getVerifyUsul($data);
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('verifikator/verify',$data);
		
	}
	
	
	
	public function getFile()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$p         = $this->myencrypt->decode($this->input->get('p'));
		
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 		
		readfile(base_url().'uploads/'.$instansi.'/'.$file);
		
	}	
	
	
	public function getKelengkapan()	{
		
		$param  = $this->myencrypt->decode($this->input->get('id'));
		$res	= $this->_arrayUnique(explode(',',$param));
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>STATUS</th>
								<th>BERKAS YANG DI UPLOAD</th></tr></thead>';
		for($i=0;$i<count($res);$i++)
		{
		    $html .='<tr>
						<td><i class="fa fa-check" style="color:green;"></i></td>	
						<td>'.$res[$i].'</td></tr>';	
		}
		$html .='</table>';
		
		echo $html;
	}
	
	function _arrayUnique($array, $preserveKeys = false)  
	{  
		// Unique Array for return  
		$arrayRewrite = array();  
		// Array with the md5 hashes  
		$arrayHashes = array();  
		foreach($array as $key => $item) {  
			// Serialize the current element and create a md5 hash  
			$hash = md5(serialize($item));  
			// If the md5 didn't come up yet, add the element to  
			// to arrayRewrite, otherwise drop it  
			if (!isset($arrayHashes[$hash])) {  
				// Save the current element hash  
				$arrayHashes[$hash] = $hash;  
				// Add element to the unique Array  
				if ($preserveKeys) {  
					$arrayRewrite[$key] = $item;  
				} else {  
					$arrayRewrite[] = $item;  
				}  
			}  
		}  
		return $arrayRewrite;  
	}

	
	public function kerja()
	{
		
		$data['nip']		 = $this->input->post('nip');
		$data['id_agenda']   = $this->input->post('id_agenda');
		$data['layanan_id']  = $this->input->post('layanan_id');		
		
		$data['response']	= $this->verifikator->setKerja($data);
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}	
	
	public function unlock()
	{
		
		$data['response']	= $this->verifikator->setUnlock($this->input->post());
		$data['data']		= $this->input->post();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}	
	
	public function save()
	{
		$this->form_validation->set_rules('status','Status', 'required');
		$this->form_validation->set_rules('catatan','Catatan', 'required');
		
		
		$data['status'] 		= $this->input->post('status');
		$data['catatan']        = $this->input->post('catatan');
		$data['nip']		    = $this->input->post('nip');
		$data['id_agenda']      = $this->input->post('id_agenda');
		$data['layanan_id']     = $this->input->post('layanan_id');
		$data['golongan']       = $this->input->post('golongan');
		$data['finish']         = $this->input->post('finish');		
		

		if ($this->form_validation->run() == FALSE)
		{
			$data['error']	    = 'Lengkapi Form';
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
		}
		else
		{
			$this->db->trans_begin();
			$data['response']	    = $this->verifikator->setVerifikator($data);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				
				$data['error']	    = 'Something, Wrong';
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			}
			else
			{			   
				$this->send_to_Telegram($data);
				
				$this->db->trans_commit();				
				
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
            }			
			
		}		
	}


	public function kinerja()
	{
		$data['menu']     		=  $this->menu->build_menu();		
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['show']		  	= FALSE;
		$data['layanan']  		= $this->verifikator->getPelayanan();
		$data['instansi']  		= $this->verifikator->getInstansi();
		$data['verifikator']  	= $this->verifikator->getVerifikator();
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('verifikator/kinerja',$data);
	}
	
	public function getKinerja()
	{	
	
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
				
	    $this->form_validation->set_rules('instansi', 'instansi','trim' );
		$this->form_validation->set_rules('layanan', 'layanan', 'trim');
		$this->form_validation->set_rules('reportrange', 'Periode', 'required');
		
		$instansi  				= $this->input->post('instansi');
		$layanan    			= $this->input->post('layanan');
		$reportrange    		= $this->input->post('reportrange');
		
		if(!empty($reportrange))
		{	
			$xreportrange       	= explode("-",$reportrange);
			$data['startdate']  	= $xreportrange[0];
			$data['enddate']		= $xreportrange[1];
		}
			
		$this->session->set_userdata('frmInstansi',$instansi);
		$this->session->set_userdata('frmLayanan',$layanan);
		
	
	    $perintah         = $this->input->post('perintah');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['menu']     =  $this->menu->build_menu();			
			$data['lname']    =  $this->auth->getLastName();        
			$data['name']     =  $this->auth->getName();
			$data['jabatan']  =  $this->auth->getJabatan();
			$data['member']	  =  $this->auth->getCreated();
			$data['avatar']	  =  $this->auth->getAvatar();
			
			$data['layanan']  	  = $this->verifikator->getPelayanan();
			$data['instansi']  	  = $this->verifikator->getInstansi();
			$data['verifikator']  = $this->verifikator->getVerifikator();	
			$data['show']		  = FALSE;
			
			$this->load->view('verifikator/kinerja',$data);
		}
        else
        {			
				
			if($perintah == 1)
			{
				$data['menu']     =  $this->menu->build_menu();				
				$data['lname']    =  $this->auth->getLastName();        
				$data['name']     =  $this->auth->getName();
				$data['jabatan']  =  $this->auth->getJabatan();
				$data['member']	  =  $this->auth->getCreated();
				$data['avatar']	  =  $this->auth->getAvatar();
				
				$data['layanan']  	  = $this->verifikator->getPelayanan();
				$data['instansi']  	  = $this->verifikator->getInstansi();
				$data['verifikator']  = $this->verifikator->getVerifikator();			
				$data['show']		  = TRUE;
				
				if($instansi != 9)
				{	
					$q                    = $this->verifikator->getKinerja($this->input->post());
					$data['usul']  		  = $q;
					$this->load->view('verifikator/kinerja',$data);
				}
				else
				{
					$q                    = $this->verifikator->getKinerjaTaspen($this->input->post());
					$data['usul']  		  = $q;
					$this->load->view('verifikator/kinerjaTaspen',$data);
				}
				
				
				
			}
			else
			{	
				if($instansi != 9)
				{	
					$q                    = $this->verifikator->getKinerja($this->input->post());	
					$this->_getExcel($q,$data);
				}
				else
				{
					$q                    = $this->verifikator->getKinerjaTaspen($this->input->post());
					$this->_getExcelTaspen($q,$data);
				}
				
				
			}		
		}	
	}
	
	private function _getExcel($q,$data)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "KINERJA VERIFIKATOR ".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'KINERJA VERIFIKATOR<br/>';		
		$html .= 'Periode Verifikasi : '.$data['startdate'].' sampai dengan '.$data['enddate'].'<br/>';	
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NO</th>
					<th>NIP</th>
					<th>NAMA</th>
					<th>INSTANSI</th>
					<th>USUL</th>
					<th>TANGGAL USUL</th>
					<th>TANGGAL VERIFIKATOR</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					<th>LAYANAN</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->nama}</td>";					
				$html .= "<td>{$r->instansi}</td>";	
				$html .= "<td>{$r->agenda_nousul}</td>";	
				$html .= "<td>{$r->agenda_timestamp}</td>";	
				$html .= "<td>{$r->verify_date}</td>";	
				$html .= "<td>{$r->nomi_status}</td>";
				$html .= "<td>{$r->nomi_alasan}</td>";	
				$html .= "<td>{$r->layanan_nama}</td>";	
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


    /*TASPEN*/
    public function verifyGetTaspen()
	{
		$data['usul_id'] 	=  $this->myencrypt->decode($this->input->get('i'));
		$data['nip'] 		=  $this->myencrypt->decode($this->input->get('n'));
		$data['layanan_id'] =  $this->myencrypt->decode($this->input->get('p'));		
		
			
		$this->session->set_userdata($data);
		
		$data['menu']     =  $this->menu->build_menu();	
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['tabs']  	  = $this->verifikator->getAllTabTaspen($data);
		$data['dokumen']  = $this->verifikator->getAllDokumenTaspen($data);
		$data['usul']	  = $this->verifikator->getVerifyUsulTaspen($data);
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}		
		$this->load->view('verifikator/verifyTaspen',$data);
		
	}
		
	public function getFileTaspen()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$p         = $this->myencrypt->decode($this->input->get('p'));
		$t         = $this->myencrypt->decode($this->input->get('t'));
		
		
		ob_clean();
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:'.$t);
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 		
		readfile(base_url().'uploads/taspen/'.$file);
		
	}	
	
	public function kerjaTaspen()
	{
		
		$data['nip']		 = $this->input->post('nip');
		$data['usul_id']     = $this->input->post('usul_id');
		$data['layanan_id']  = $this->input->post('layanan_id');		
		
		$data['response']	= $this->verifikator->setKerjaTaspen($data);
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}	
	
	public function saveTaspen()
	{
		$this->form_validation->set_rules('status','Status', 'required');
		$this->form_validation->set_rules('catatan','Catatan', 'required');
		
		
		$data['usul_status'] 		= $this->input->post('status');
		$data['usul_alasan']        = $this->input->post('catatan');
		$data['nip']		        = $this->input->post('nip');
		$data['usul_id']            = $this->input->post('usul_id');
		$data['layanan_id']         = $this->input->post('layanan_id');		
			
		

		if ($this->form_validation->run() == FALSE)
		{
			$data['error']	    = 'Lengkapi Form';
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
		}
		else
		{
			$this->db->trans_begin();
			$data['response']	    = $this->verifikator->setHasilVerifikatorTaspen($data);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				
				$data['error']	    = 'Something, Wrong';
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			}
			else
			{			   
				$this->db->trans_commit();				
				
				$this->send_taspen_Telegram($data);
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
            }				
		}
		
		
		
	}
	
	
	public function verifyPostTaspen()
	{
		
		$data['nip'] 		=  $this->input->post('nip');		
		
		$data['usul_id']    =  $this->session->userdata('usul_id');
		$data['layanan_id'] =  $this->session->userdata('layanan_id');
		
		
		$data['menu']     =  $this->menu->build_menu();			
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['tabs']  	  = $this->verifikator->getAllTabTaspen($data);
		$data['dokumen']  = $this->verifikator->getAllDokumenTaspen($data);
		$data['usul']	  = $this->verifikator->getVerifyUsulTaspen($data);
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('verifikator/verifyTaspen',$data);
		
	}
	
	private function _getExcelTaspen($q,$data)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "KINERJA VERIFIKATOR ".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'KINERJA VERIFIKATOR<br/>';		
		$html .= 'Periode Verifikasi : '.$data['startdate'].' sampai dengan '.$data['enddate'].'<br/>';	
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NO</th>
					<th>NIP</th>
					<th>NAMA PNS</th>
					<th>NAMA</th>
					<th>USUL</th>
					<th>TANGGAL USUL</th>
					<th>TANGGAL VERIFIKATOR</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					<th>LAYANAN</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->nama_pns}</td>";	
				$html .= "<td>{$r->nama_janda_duda}</td>";	
				$html .= "<td>{$r->nomor_usul}</td>";	
				$html .= "<td>{$r->tgl_usul}</td>";	
				$html .= "<td>{$r->usul_verif_date}</td>";	
				$html .= "<td>{$r->usul_status}</td>";
				$html .= "<td>{$r->usul_alasan}</td>";	
				$html .= "<td>{$r->layanan_nama}</td>";	
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
	
	public function unlockTaspen()
	{
		
		$data['response']	= $this->verifikator->setUnlockTaspen($this->input->post());
		$data['data']		= $this->input->post();
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}	
	
	/* Kirim Notifikasi Telegram ke TASPEN*/
	
	function send_taspen_Telegram($data)
	{
		$usul_id        = $data['usul_id'];
		$nip			= $data['nip'];
		
		$row_usul	    =  $this->verifikator->getUsul_byid($data)->row();
		$TelegramAkun   =  $this->verifikator->getTelegramAkun_byUserId($row_usul->kirim_bkn_by);
				
		if($TelegramAkun->num_rows() > 0)
		{	
			foreach($TelegramAkun->result() as $value)
			{	
				// send to telegram API
				if(!empty($value->telegram_id))
				{	
					$this->telegram->sendApiAction($value->telegram_id);
					$text  = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong>  Berkas kamu sudah selesai verifikasi dengan hasil berikut ini :";
					$text .= "\n Tanggal :".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul :".$row_usul->nomor_usul;
					$text .= "\n Layanan :".$row_usul->layanan_nama;
					$text .= "\n NIP :".$row_usul->nip;
					$text .= "\n Nama PNS :".$row_usul->nama_pns;
					$text .= "\n Tahapan :".$row_usul->tahapan_nama;
					$text .= "\n Status Berkas :".$row_usul->usul_status;
					$text .= "\n Keterangan :".$row_usul->usul_alasan;
					$text .= "</pre>";
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
									
				}	
			}
		}
	}	
	
	/* Kirim Notifikasi Telegram ke Instansi*/
	
	function send_to_Telegram($data)
	{
		$agenda_id      = $data['id_agenda'];
		$nip			= $data['nip'];
		
		$row_agenda	    =  $this->verifikator->getAgenda_byid($agenda_id,$nip)->row();
		$TelegramAkun   =  $this->verifikator->getTelegramAkun_byInstansi($row_agenda->agenda_ins);
				
		if($TelegramAkun->num_rows() > 0)
		{	
			foreach($TelegramAkun->result() as $value)
			{	
				// send to telegram API
				if(!empty($value->telegram_id))
				{	
					$this->telegram->sendApiAction($value->telegram_id);
					$text  = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong>  Berkas kamu sudah selesai verifikasi dengan hasil berikut ini :";
					$text .= "\n Tanggal :".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul :".$row_agenda->agenda_nousul;
					$text .= "\n Layanan :".$row_agenda->layanan_nama;
					$text .= "\n NIP :".$row_agenda->nip;
					$text .= "\n Nama PNS :".$row_agenda->PNS_GLRDPN.' '.$row_agenda->PNS_PNSNAM.' '.$row_agenda->PNS_GLRBLK;
					$text .= "\n Tahapan :".$row_agenda->tahapan_nama;
					(!empty($row_agenda->status_level_satu) ? $text .= "\n Status Level 1 :".$row_agenda->status_level_satu : '');
					(!empty($row_agenda->status_level_dua)  ? $text .= "\n Status Level 2 :".$row_agenda->status_level_dua : '');
					(!empty($row_agenda->status_level_tiga) ? $text .= "\n Status Level 3 :".$row_agenda->status_level_tiga : '');
					$text .= "\n Status Berkas :".$row_agenda->nomi_status;
					$text .= "\n Keterangan :".$row_agenda->nomi_alasan;
					$text .= "\n Instansi :".$row_agenda->instansi.'</pre>';
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
										
				}	
			}
		}
	}	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */