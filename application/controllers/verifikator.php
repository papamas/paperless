
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
		$data['pnsDataOracle'] = $this->verifikator->getPnsdataOracle($data['nip']);
		
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
		ob_end_clean();
		readfile(base_url().'uploads/'.$instansi.'/'.$file);
		
	}	
	
	
	public function getFilePengantar()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$p         = $this->myencrypt->decode($this->input->get('p'));
		
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 		
		ob_end_clean();
		readfile(base_url().'agenda/'.$instansi.'/'.$file);
		
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
		$data['kpp_status']     = ($this->input->post('kpp_status') == 2 ? NULL : $this->input->post('kpp_status'));			
		

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
					<th>LAYANAN</th>
					<th>USUL</th>					
					<th>LEVEL 1</th>
					<th>LEVEL 2</th>
					<th>LEVEL 3</th>
					<th>TANGGAL SELESAI</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->nama}</td>";					
				$html .= "<td>{$r->instansi}</td>";	
				$html .= "<td>{$r->layanan_nama}</td>";	
				$html .= "<td>{$r->agenda_nousul}<br/>{$r->agenda_timestamp}</td>";					
                $html .= "<td>{$r->status_level_satu}<br/>{$r->verif_name_satu}<br/>{$r->verifdate_level_satu}</td>";	
                $html .= "<td>{$r->status_level_dua}<br/>{$r->verif_name_dua}<br/>{$r->verifdate_level_dua}</td>";	
                $html .= "<td>{$r->status_level_tiga}<br/>{$r->verif_name_tiga}<br/>{$r->verifdate_level_tiga}</td>";	
               	$html .= "<td>{$r->verify_date}</td>";	
				$html .= "<td>{$r->nomi_status}</td>";
				$html .= "<td>{$r->nomi_alasan}</td>";	
				
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
		
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:'.$t);
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
        ob_end_clean();		
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
					$text .= "\n Tanggal:".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul:".$row_usul->nomor_usul;
					$text .= "\n Layanan:".$row_usul->layanan_nama;
					$text .= "\n NIP:".$row_usul->nip;
					$text .= "\n Nama PNS:".$row_usul->nama_pns;
					$text .= "\n Tahapan:".$row_usul->tahapan_nama;
					$text .= "\n Status Berkas:".$row_usul->usul_status;
					$text .= "\n Keterangan:".$row_usul->usul_alasan;
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
					$text .= "\n Tanggal:".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul:".$row_agenda->agenda_nousul;
					$text .= "\n Layanan:".$row_agenda->layanan_nama;
					$text .= "\n NIP:".$row_agenda->nip;
					$text .= "\n Nama PNS:".$row_agenda->PNS_GLRDPN.' '.$row_agenda->PNS_PNSNAM.' '.$row_agenda->PNS_GLRBLK;
					$text .= "\n Tahapan:".$row_agenda->tahapan_nama;
					(!empty($row_agenda->status_level_satu) ? $text .= "\n Status Level 1 :".$row_agenda->status_level_satu : '');
					(!empty($row_agenda->status_level_dua)  ? $text .= "\n Status Level 2 :".$row_agenda->status_level_dua : '');
					(!empty($row_agenda->status_level_tiga) ? $text .= "\n Status Level 3 :".$row_agenda->status_level_tiga : '');
					$text .= "\n Status Berkas:".$row_agenda->nomi_status;
					$text .= "\n Keterangan:".$row_agenda->nomi_alasan;
					$text .= "\n Instansi:".$row_agenda->instansi.'</pre>';
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
										
				}	
			}
		}
	}	
	
	public function draftTaspen()
	{
		$data['usul_id']  = $this->myencrypt->decode($this->input->get('u'));
		$data['nip']      = $this->myencrypt->decode($this->input->get('n'));
		$layanan		  = $this->myencrypt->decode($this->input->get('l'));
		$data['layanan']  = $layanan;
		
		switch($layanan){
			case 15:
				$this->_cetakMutasi($data);			
			break;
			case 16:
				$this->_cetakSK($data);
			break;
			case 17:
				$this->_cetakSK($data);
			break;
		}		
		
	}

	function _cetakMutasi($data)
	{
		$row						= $this->verifikator->getEntryOneTaspen($data)->row();
		$mutasiIstri				= $this->verifikator->getMutasiIstri($row->usul_id);
		$mutasiAnak				    = $this->verifikator->getMutasiAnak($row->usul_id);
				
		$this->load->library('PDF', array());	
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);		
		
		$this->pdf->SetAutoPageBreak(false, 0);
		
		$this->pdf->SetFont('freeSerif', '', 8);
		$this->pdf->AddPage('L', 'FOLIO', false, false);
		
		$this->pdf->Text(225, 10, 'LAMPIRAN XVII SURAT EDARAN BERSAMA KEPALA BADAN ADMINISTRASI');
		$this->pdf->Text(225, 15, 'KEPEGAWAIAN NEGARA DAN DIREKTUR JENDERAL ANGGARAN');
		$this->pdf->Text(225, 20, 'NOMOR : 19/SE/1989');
		$this->pdf->Text(225, 25, 'NOMOR : SE-51/A/1989');
		$this->pdf->Text(225, 30, 'TANGGAL : 14 APRIL 1989');
		
		
		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(100, 45, 'FORMULIR PENDAFTARAN ISTRI(2)/SUAMI/ANAK(2)');
		$this->pdf->Text(115, 50, '( untuk penerima pensiun pegawai )');
		
		$this->pdf->SetFont('freeSerif', '', 9);
		$tbl ='
<table  cellspacing="0" cellpadding="1" border="1">
    <tr style="background-color:#EAEDED;">
        <td align="center" rowspan="2" width="25px;"> NO</td>
        <td  rowspan="2"> NAMA ANAK(2)<br/>&nbsp;KANDUNG</td>
        <td  rowspan="2" width="25px;"> LK/<br/>&nbsp;PR</td>
		<td  rowspan="2"> TANGGAL<br/>&nbsp;LAHIR</td>	
		<td  colspan="3" width="345px;"> KETERANGAN TENTANG IBU/AYAH</td>
		<td  colspan="3" width="330px;"> KETERANGAN TENTANG PENERIMA PENSIUN PEGAWAI</td>
       	
    </tr>
	<tr style="background-color:#EAEDED;">
        <td width="115px;"> NAMA</td>
        <td width="115px;"> CERAI<br/>&nbsp;TANGGAL</td>
		<td width="115px;"> MENINGGAL<br/>&nbsp;TANGGAL</td>	    		
    </tr>';
	$j=1;
	if($mutasiAnak->num_rows() > 0){	
	foreach($mutasiAnak->result() as $value){	
        ($j == 1 ? $n='Ke/p' : $n=$j);	
		$tbl .='<tr>
			<td align="center"> '.$n.'</td>
			<td> '.$value->nama.'</td>
			<td> '.$value->sex.'</td>
			<td> '.$value->atgl_lahir.'</td>
			<td> '.$value->nama_ibu_ayah.'</td>
			<td> '.$value->acerai_tgl.'</td>
			<td> '.$value->ameninggal_tgl.'</td>		
		</tr>';
		$j++;
		}		
	}

    $anak = $mutasiAnak->num_rows();	
	$i = $anak + 1;
	for($i;$i <= 7;$i++){	
	$tbl .='<tr>
        <td align="center"> '.$i.'.</td>
        <td> </td>
        <td> </td>
		<td> </td>
		<td> </td>
		<td> </td>
		<td> </td>
    </tr>';
	 
	}
    $tbl.='<tr style="background-color:#EAEDED;">
        <td align="center"  width="25px;"> NO</td>
        <td> ISTRI(2) SUAMI</td>
        <td  colspan="2"> ISTRI PERTAMA/<br>&nbsp;SUAMI</td>
		<td> ISTRI KEDUA/<br>&nbsp;SUAMI</td>	
		<td> ISTRI KETIGA/<br>&nbsp;SUAMI</td>
		<td> ISTRI KEEMPAT</td>       	
    </tr>';
	$tbl.='<tr>
        <td align="center"> 1.</td>
        <td> Nama</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->nama.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 2.</td>
        <td> Nama Kecil</td>';		
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->nama_kecil.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	
	$tbl .='<tr>
        <td align="center"> 3.</td>
        <td> Tempat/Tgl Lahir</td>';		
    $k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->tempat_lahir.'/'.$value->atgl_lahir.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 4.</td>
        <td> Tanggal Nikah</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_nikah.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 5.</td>
        <td> Tanggal Pendaftaran</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_pendaftaran.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 6.</td>
        <td> Tanggal Cerai</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_cerai.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center">7.</td>
        <td> Tanggal Wafat</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_wafat.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center">8.</td>
        <td > Alamat</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->alamat.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 9.</td>
        <td rowspan="11"> Tanda Tangan<br/>&nbsp;Atau Cap Jempol<br/>&nbsp;Tangan Kiri</td>
        <td rowspan="11" colspan="2"> </td>
		<td rowspan="11"> </td>
		<td rowspan="11"> </td>
		<td rowspan="11"> </td>
    </tr>
	<tr>
        <td align="center"> 10.</td> 
	</tr>
	<tr>
        <td align="center"> 11.</td> 
	</tr>
	<tr>
        <td align="center"> 12.</td> 
	</tr>
	<tr>
        <td align="center"> 13.</td> 
	</tr>
	<tr>
        <td align="center"> 14.</td> 
	</tr>
	<tr>
        <td align="center"> 15.</td> 
	</tr>';
	if($mutasiAnak->num_rows() < 3)
	{	
	$tbl.='<tr>
        <td align="center"> 16.</td> 
	</tr>
	<tr>
        <td align="center"> 17.</td> 
	</tr>';
	}
	
	$tbl .='</table>';

        $this->pdf->SetXY(10, 60);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');	
		
		$this->pdf->Text(212, 65, 'Yang Bertanda Tangan dibawah ini :');
		$this->pdf->Text(212, 70, '1. Nama :');
		$this->pdf->Text(260, 70, ': '.$row->nama_pns);
		
		$this->pdf->Text(212, 75, '2. Nama Kecil ');
		$this->pdf->Text(260, 75, ': '.$row->nama_kecil);
		
		$this->pdf->Text(212, 80, '3. Tempat/Tanggal Lahir ');
		$this->pdf->Text(260, 80, ': '.$row->tempat_lahir.', '.$row->atgl_lahir);
		
		$this->pdf->Text(212, 85, '4. Tgl.No.Surat Keputusan Pensiun ');
		$this->pdf->Text(260, 85, ': '.$row->atgl_skep);
		$this->pdf->Text(260, 90, ': '.$row->nomor_skep);
		
		$this->pdf->Text(212, 95, '5. Pensiun Pokok ');
		$this->pdf->Text(260, 95, ': Rp. '.$row->penpok);
		
		$this->pdf->Text(212, 100, '6. Pensiun Terhitung Mulai ');
		$this->pdf->Text(260, 100, ': '.$row->pensiun);
		
		$this->pdf->Text(212, 105, '7. Alamat ');
		$this->pdf->Text(260, 105, ': ');
		$text1= $row->alamat;
		$this->pdf->writeHTMLCell(65,'',261,105,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(212, 125, '8. Tanda Tangan ');
		$this->pdf->Text(260, 125, ': TTD');
		
		$this->pdf->Text(212, 140, 'Disahkan Tanggal ');
		$this->pdf->Text(260, 140, ': '.'XX XXXXX XXXX');
		
		$this->pdf->Text(212, 145, 'Nomor ');
		$this->pdf->Text(260, 145, ': '.'XX/XX/XX/XXX/XXXX');
		
		$this->pdf->writeHTMLCell(75,'',235,155,'AN. KEPALA KANTOR',0,0,false,false,'C',true);
		$this->pdf->writeHTMLCell(75,'',235,160,'REGIONAL XI BADAN KEPEGAWAIAN NEGARA',0,0,false,false,'C',true);
		$text1= strtoupper('Kepala Bidang Pengangkatan dan Pensiun');
		$this->pdf->writeHTMLCell(75,'',235,164,$text1,0,0,false,false,'C',true);
		$this->pdf->Text(250, 185, strtoupper('XXX XXXXXXXXX XXXXXX').(!empty('S.Sos') ? ','.'S.Sos, Msi' : ''));
		$this->pdf->Text(250, 189, 'NIP. '.'19XXXXXXXXXXXXXXXX');
		
		
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = ' SK Mutasi Keluarga  PNS '.$row->nama_pns ;				
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N');
		// Awal Water mark
		$vfont = "Helvetica";
		$vfontsize = 75;
		$vfontbold = "B";
		// Calcular ancho de la cadena
		$widthtext = $this->pdf->GetStringWidth(trim('DRAFT'), $vfont, $vfontbold, $vfontsize, false );
		$widthtextcenter = round(($widthtext * sin(deg2rad(45))) / 2 ,0);
		// Get the page width/height
		$myPageWidth = $this->pdf->getPageWidth();
		$myPageHeight = $this->pdf->getPageHeight();
		// Find the middle of the page and adjust.
		$myX = ( $myPageWidth / 2 ) - $widthtextcenter;
		$myY = ( $myPageHeight / 2 ) + $widthtextcenter;
		$this->pdf->SetAlpha(0.2);
		$this->pdf->StartTransform();
		$this->pdf->Rotate(30, $myX-90, $myY+50);
		$this->pdf->SetFont($vfont, $vfontbold, $vfontsize);
		$this->pdf->Text($myX, $myY ,trim('DRAFT'));
		$this->pdf->StopTransform();
		$this->pdf->SetAlpha(1);
		
		// break HALAMAN 2
		$this->pdf->AddPage('P', 'A4', false, false);
		$garuda = base_url() . 'assets/dist/img/garuda.png';
		$this->pdf->Image($garuda, 5, 8, 23, '', 'PNG', '', 'T', false, 145, 'C', false, false, 0, false, false, false);
		
		$this->pdf->SetFont('helvetica', 'B', 12);
		$this->pdf->Text(5, 35,'BADAN KEPEGAWAIAN NEGARA', false, false, true, 0, 4, 'C', false, '', 0, false, 'T', 'M', false);
		$this->pdf->Text(5, 40, 'KANTOR REGIONAL XI', false, false, true, 0, 4, 'C', false, '', 0, false, 'T', 'M', false);
		$style = array(
			'width' => 0.29999999999999999,
			'cap'   => 'butt',
			'join'  => 'miter',
			'dash'  => 0,
			'color' => array(0, 0, 0)
			);
		$this->pdf->Line(5, 46, $this->pdf->getPageWidth() - 5, 46, $style);
		$style1 = array(
			'width' => 1,
			'cap'   => 'butt',
			'join'  => 'miter',
			'dash'  => 0,
			'color' => array(0, 0, 0)
			);
		$this->pdf->Line(5, 47, $this->pdf->getPageWidth() - 5, 47, $style1);
		
		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(150, 50, 'Manado, '.'XX XXXXX XXXX');
		
		$this->pdf->Text(5, 55, 'Nomor ');
		$this->pdf->Text(25, 55, ': '.'XX/XX/XX/XXX/XXXX');
		
		$this->pdf->Text(5, 60, 'Lampiran ');
		$this->pdf->Text(25, 60, ':  ');
		
		$this->pdf->Text(5, 65, 'Perihal ');
		$this->pdf->Text(25, 65, ': Pengambilan formulir ');
		$this->pdf->Text(27, 70, 'Model A/II/1969 Pens ');
		
		$this->pdf->Text(140, 60, 'Kepada');
		$this->pdf->Text(130, 65, 'Yth.');
		$this->pdf->Text(140, 65,$row->nama_pns);
		$this->pdf->Text(140, 70, 'NIP. '.$row->nip);
		$this->pdf->Text(130, 75, 'D/a. ');
		$text1=$row->alamat;
		$this->pdf->writeHTMLCell(70,'',140,75,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 100, '1.');
		$text1='Menunjuk Surat dari Ka. PT. Taspen (persero) Cabang '.$row->nama_taspen.'  Nomor '.$row->nomor_usul.' Perihal permohonan Saudara Tanggal '.$row->atgl_usul.' untuk mengesahkan/mencatat mutasi keluarga, bersama ini kami kirimkan kembali Formulir Model A/II/Pens, tentang pendataran Isteri/Suami/Anak sebagai yang berhak menerima pensiun Janda/Duda yang telah disahkan/dicatat.';
		$this->pdf->writeHTMLCell(175,'',30,100,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 125, '2.');
		$text1='Mengingat bahwa bukti pendaftaran tersebut sangat penting sebagai kelengkapan permohonan pensiun Janda/Duda sebagai Isteri/Suami/Anak/Saudara, kami harapkan agar formulir tersebut disimpan dengan baik.';
		$this->pdf->writeHTMLCell(175,'',30,125,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 145, '3.');
		$text1='Perlu kami jelaskan bahwa pendaftaran yang saudara lakukan telah melebihi batas waktu 1 (satu) tahun setelah terjadinya perkawinan tersebut sebagaimana ditetapkan dalam pasal 19 ayat 6 Undang-Undang Nomor 11 Tahun 1969, maka pendaftaran tersebut hanya kami catat, tetapi tidak disahkan.';
		$this->pdf->writeHTMLCell(175,'',30,145,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 165, '4.');
		$text1='Demikian untuk dipergunakan sebagaimana mestinya.';
		$this->pdf->writeHTMLCell(175,'',30,165,$text1,0,0,false,false,'J',true);
		
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = 'SK Mutasi Keluarga PNS '.$row->nama_pns;					
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 20, 190, 25, 25, $style, 'N'); 
		
		$this->pdf->Text(125, 175, 'an.');
		$text2='Kepala Kantor Regional XI Badan Kepegawaian Negara '.'Kepala Bidang Pengangkatan Pensiun';
		$this->pdf->writeHTMLCell(75,125,130,175,$text2,0,0,false,true,'L',true);
		
		$this->pdf->Text(130, 215,ucwords(strtolower('XXX XXXXXXX XXXXXXX')).(!empty('S.Sos') ? ','.'S.Sos, Msi' : ''));
		$this->pdf->Text(130, 220, 'NIP. '.'19XXXXXXXXXXXXXXXX');
		
		$this->pdf->Text(20, 225, 'Tembusan, Yth :');
		$this->pdf->Text(20, 230, '1. Kepala Kantor Cabang PT. Taspen (Persero) di '.$row->nama_taspen);
		$this->pdf->Text(20, 235, '2. Direktur Pensiun PNS dan Pejabat Negara BKN di Jakarta');
		
		// Awal Water mark
		$vfont = "Helvetica";
		$vfontsize = 75;
		$vfontbold = "B";
		// Calcular ancho de la cadena
		$widthtext = $this->pdf->GetStringWidth(trim('DRAFT'), $vfont, $vfontbold, $vfontsize, false );
		$widthtextcenter = round(($widthtext * sin(deg2rad(45))) / 2 ,0);
		// Get the page width/height
		$myPageWidth = $this->pdf->getPageWidth();
		$myPageHeight = $this->pdf->getPageHeight();
		// Find the middle of the page and adjust.
		$myX = ( $myPageWidth / 2 ) - $widthtextcenter;
		$myY = ( $myPageHeight / 2 ) + $widthtextcenter;
		$this->pdf->SetAlpha(0.2);
		$this->pdf->StartTransform();
		$this->pdf->Rotate(30, $myX-90, $myY+50);
		$this->pdf->SetFont($vfont, $vfontbold, $vfontsize);
		$this->pdf->Text($myX, $myY ,trim('DRAFT'));
		$this->pdf->StopTransform();
		$this->pdf->SetAlpha(1);
		ob_end_clean();
		$this->pdf->Output('DraftSKMutasiKeluarga.pdf', 'D');
	}	
	
	
	function _cetakSK($data)
	{
		$layanan             		= $data['layanan'];
		$result						= $this->verifikator->getEntryOneTaspen($data);
		$row						= $result->row();
		
		switch($layanan){			
			case 16:
				$name   = 'Janda/Duda';
				
				if($row->jd_dd_status == 1)
				{
					$lname  = 'DD.ALM';
				}
				else
				{
					$lname  = 'JD.ALM';
				}
			break;
			case 17:
				$name  = 'Yatim';	
				$lname  = 'YT.ALM';
			break;
		}		
		
		
       	
	   	$this->load->library('PDF', array());	
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);		
		
		$this->pdf->SetAutoPageBreak(TRUE, 0);
		
		$this->pdf->SetFont('freeSerif', '', 8);
		$this->pdf->AddPage('L', 'FOLIO', false, false);
		
		
		$this->pdf->Text(41, 37, 'KEPUTUSAN KEPALA BADAN KEPEGAWAIAN NEGARA');
		$this->pdf->Text(50, 40, 'NOMOR: '.'XX/XX/XX/XXX/XXXX');
		$this->pdf->Text(48, 45, 'KEPALA BADAN KEPEGAWAIAN NEGARA');
		
		
		$this->pdf->Text(5, 50, 'Menimbang');
		$this->pdf->Text(30, 50, ':');
		$this->pdf->Text(33, 50, '1. ');
		$text1='bahwa Pegawai Negeri Sipil/pensiunan Pegawai Negeri Sipil *) atas nama Saudara  '.$row->nama_pns.' NIP/NP '.$row->nip.'  telah meninggal dunia pada tanggal '.$row->meninggal;
		$this->pdf->writeHTMLCell(125,'',36,50,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(33, 58, '2. ');
		$text1='bahwa yang namanya tercantum dalam keputusan ini, memenuhi syarat untuk diberikan pensiun '.$name;
		$this->pdf->writeHTMLCell(125,'',36,58,$text1,0,0,false,false,'J',true);
		
		
		$this->pdf->Text(5, 65, 'Mengingat');
		$this->pdf->Text(30, 65, ':');
		$this->pdf->Text(33, 65, '1. Undang- Undang Nomor 11 Tahun 1969;');
		$this->pdf->Text(33, 69, '2. Undang-Undang Nomor 8 Tahun 1974 jo, Undang-Undang Nomor 5 Tahun 2014;');
		$this->pdf->Text(33, 73, '3. Peraturan Pemerintah Nomor 7 tahun 1977 jo. Peraturan Pemerintah Nomor 18 Tahun 2019;');
		$this->pdf->Text(33, 77, '4. Peraturan Pemerintah Nomor 32 Tahun 1979 jo. Peraturan Pemerintah Nomor 19 Tahun 2013;');
		$this->pdf->Text(33, 81, '5. Peraturan Pemerintah Nomor 99 Tahun 2000 jo. Peraturan Pemerintah Nomor 12 Tahun 2002;');
		$this->pdf->Text(33, 85, '6. Peraturan Pemerintah Nomor 9 Tahun 2003 jo Peraturan Pemerintah Nomor 63 Tahun 2009;');
		$this->pdf->Text(33, 89, '7. Surat Kepala BKN Nomor WK-26-30/V33-5/99 Tanggal 30 Januari 2012;');
		
		
		
		$this->pdf->Text(70, 99, 'MEMUTUSKAN');
		$this->pdf->Text(5, 103, 'Menetapkan');
		$this->pdf->Text(30, 103, ':');
		$this->pdf->Text(5, 106, 'PERTAMA');
		$this->pdf->Text(30, 106, ':');
		
		$text1='Kepada yang namanya tercantum dalam lajur 1 terhitung mulai tanggal tersebut dalam lajur 9, diberikan pensiun pokok sebulan sebesar tersebut dalam lajur 11 keputusan ini.';
		$this->pdf->writeHTMLCell(125,'',33,106,$text1,0,0,false,false,'J',true);
		
		$tbl = <<<EOD
<table width="50%" cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td width="25px;" align="center">1</td>
        <td width="125px;"> NAMA</td>
        <td width="250px;"> $row->nama_janda_duda</td>
		<td width="50px;"> $lname</td>
    </tr>
    <tr>
        <td width="25px;" align="center">2</td>
        <td> NAMA PNS/PENSIUN PNS *)</td>
        <td colspan="2"> $row->nama_pns</td>		
    </tr>
	<tr>
        <td width="25px;" align="center">3</td>
        <td> NIP/NRP</td>
        <td colspan="2"> $row->nip</td>		
    </tr>
	<tr>
        <td width="25px;" align="center">4</td>
        <td> PANGKAT/GOL. RUANG</td>
        <td colspan="2"> $row->GOl_PKTNAM / $row->GOL_GOLNAM</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">5</td>
        <td> JABATAN </td>
        <td colspan="2"> $row->jabatan</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">6</td>
        <td> UNIT KERJA TERAKHIR </td>
        <td colspan="2"> $row->unit_kerja</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">7</td>
        <td> TANGGAL PERKAWINAN </td>
        <td colspan="2"> $row->perkawinan</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">8</td>
        <td> MENINGGAL DUNIA </td>
        <td colspan="2"> $row->meninggal</td>		
    </tr>
		
	<tr>
        <td width="25px;" align="center">9</td>
        <td> PENSIUN TMT </td>
        <td colspan="2"> $row->pensiun</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">10</td>
        <td> GAJI POKOK TERAKHIR </td>
        <td colspan="2"> Rp. $row->gapok,-</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">11</td>
        <td> PENSIUN POKOK </td>
        <td> Rp. $row->penpok,- </td>
		<td> PP. 18/2019</td>		
    </tr>

</table>
EOD;

        $this->pdf->SetXY(5, 116);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');
		
		$this->pdf->Text(5, 170, 'KEDUA');
		$this->pdf->Text(33, 170, ':');
		$text1='Mencatat bahwa anak penerima pensiun tersebut di atas pada akhir bulan terdiri dari:';
		$this->pdf->writeHTMLCell(165,'',41,170,$text1,0,0,false,false,'J',true);
		
		$tbl ='<table width="42%" cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th width="25px;" align="center">NO</th>
        <th width="152px;" align="center"> NAMA</th>
        <th align="center"> TGL LAHIR</th>
		<th align="center"  width="125px;"> NAMA<br/> AYAH/IBU</th>
		<th align="center"> KETERANGAN</th>
    </tr>';
	
	if(!empty($row->nama_anak))
	{	
		$no =1;
		foreach($result->result() as $value){	
			$tbl .='<tr>
				<td align="center"> '.$no.'</td>
				<td> '.$value->nama_anak.'</td>
				<td align="center"> '.$value->tgl_lahir_anak.'</td>
				<td align="center"> '.$value->nama_ayah.'/'.$value->nama_ibu.'</td>
				<td align="center"> '.$value->keterangan.'</td>
			</tr>';
			$no++;
		}
	}
	else
	{
		$tbl .='<tr>
			<td height="65px;"></td>
			<td height="65px;"></td>
			<td height="65px;"></td>
			<td height="65px;"></td>
			<td height="65px;"></td>
		</tr>';
	}
    $tbl .='</table>';

		$this->pdf->SetXY(5, 175);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');
		
		$this->pdf->Text(170, 32, 'KETIGA');
		$this->pdf->Text(190, 32, ':');
		$this->pdf->Text(193, 32, 'Pembayaran pensiun janda/duda tersebut dilakukan dengan ketentuan:');
		$this->pdf->Text(193, 36, 'a.');
		$text1='Pemberian dan pembayaran pensiun janda/duda dihentikan akhir bulan janda/duda yang bersangkutan menikah lagi atau berakhir apabila meninggal dunia dan tidak terdapat lagi anak yang memenuhi syarat untuk menerima pensiun.';
		$this->pdf->writeHTMLCell(125,'',196,36,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(193, 48, 'b.');
		$text2='Jika janda/duda menikah lagi atau meninggal dunia, selama masih terdapat anak/anak-anak
		yang berusia di bawah 25 tahun tidak berpenghasilan sendiri belum pernah menikah, pensiun janda/duda 
		itu dibayarkan kepada dan atas nama anak pertama tersebut di atas untuk kepentingan anak-anak lainnya 
		terhitung mulai bulan berikutnya terjadinya pernikahan/kematian';
		$this->pdf->writeHTMLCell(125,'',196,48,$text2,0,0,false,true,'J',true);
		
		$text2='Khusus untuk janda apabila janda yang bersangkutan kemudian bercerai lagi, maka pensiun janda yang pembayarannya telah dihentikan, dibayarkan kembali mulai bulan berikutnya perceraian itu berlaku sah.';
		$this->pdf->writeHTMLCell(125,'',196,64,$text2,0,0,false,true,'J',true);
		 
		$this->pdf->Text(170, 80, 'KEEMPAT');
		$this->pdf->Text(190, 80, ':');
		$text2='Di atas pensiun pokok tersebut diberikan tunjangan keluarga dan tunjangan pangan yang berlaku bagi Pegawai Negeri Sipil dan tunjangan-tunjangan lain yang berlaku bagi penerima pensiun.';
		$this->pdf->writeHTMLCell(125,'',193,80,$text2,0,0,false,true,'J',true);
		
		$this->pdf->Text(170, 90, 'KELIMA');
		$this->pdf->Text(190, 90, ':');
		$text2='Apabila dikemudian hari ternyata terdapat kekeliruan dalam keputusan ini, akan diadakan perbaikan dan perhitungan kembali sebagaimana mestinya.';
		$this->pdf->writeHTMLCell(125,'',193,90,$text2,0,0,false,true,'J',true);
		
		$text2='Asli Keputusan ini diberikan kepada yang bersangkutan dengan alamat : '.$row->alamat;
		$this->pdf->writeHTMLCell(125,'',193,100,$text2,0,0,false,true,'J',true);
		
		$this->pdf->Text(170, 115, 'Sebagai bukti sah untuk dipergunakan sebagaimana mestinya.');
		
		$this->pdf->Text(260, 125, 'Ditetapkan di');
		$this->pdf->Text(280, 125, ':');
		$this->pdf->Text(285, 125, 'MANADO');
		
		$this->pdf->Text(260, 130, 'Pada Tanggal');
		$this->pdf->Text(280, 130, ':');
		$this->pdf->Text(285, 130, 'XX XXXXX XXXX');
		
		$this->pdf->Text(255, 135, 'an.');
		$this->pdf->Text(260, 135, 'KEPALA BADAN KEPEGAWAIAN NEGARA');
		$this->pdf->writeHTMLCell(60,'',260,139,strtoupper('Kepala Bidang Pengangkatan dan Pensiun'),0,0,false,false,'J',true);
		$this->pdf->Text(260, 170,'XXXXX XXXX XXXX'.(!empty('S.Sos') ? ','.'S.Sos, MSi' : ''));
		$this->pdf->Text(260, 174, 'NIP. '.'19XXXXXXXXXXXXXXXX');
		
	    $this->pdf->Text(170, 185, 'Tembusan, Keputusan ini disampaikan kepada :');
		$this->pdf->Text(170, 190, '1. Kepala Kantor Cabang PT.TASPEN (PERSERO)/PT.ASABRI (PERSERO) di '.$row->nama_taspen);
		$this->pdf->Text(170, 195, '2. Direktur Pensiun BKN di Jakarta;');
		$this->pdf->Text(170, 200, '3. Pertinggal ');
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = ' SK '.$name.' PNS '.$row->nama_pns.'  atas nama '.$row->nama_janda_duda ;
				
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 172, 155, 25, 25, $style, 'N');		
		
		// Awal Water mark
		$vfont = "Helvetica";
		$vfontsize = 75;
		$vfontbold = "B";
		// Calcular ancho de la cadena
		$widthtext = $this->pdf->GetStringWidth(trim('DRAFT'), $vfont, $vfontbold, $vfontsize, false );
		$widthtextcenter = round(($widthtext * sin(deg2rad(45))) / 2 ,0);
		// Get the page width/height
		$myPageWidth = $this->pdf->getPageWidth();
		$myPageHeight = $this->pdf->getPageHeight();
		// Find the middle of the page and adjust.
		$myX = ( $myPageWidth / 2 ) - $widthtextcenter;
		$myY = ( $myPageHeight / 2 ) + $widthtextcenter;
		$this->pdf->SetAlpha(0.2);
		$this->pdf->StartTransform();
		$this->pdf->Rotate(30, $myX-90, $myY+50);
		$this->pdf->SetFont($vfont, $vfontbold, $vfontsize);
		$this->pdf->Text($myX, $myY ,trim('DRAFT'));
		$this->pdf->StopTransform();
		$this->pdf->SetAlpha(1);
		ob_end_clean();
		
		$this->pdf->Output('DraftSKJDYM.pdf', 'D');
    }

    public function getAnakJdAll()
	{
		$id			= $this->input->get('usul_id');		
		$usul 		= $this->verifikator->getAnakJd($id);	
       	$html = '';
		$html .='<table id="tb-anak" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th><a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#anakModalJd" data-usul="'.$id.'" data-id="" data-tooltip="tooltip" title="Tambah Data Anak"> <i class="fa fa-plus"></i> Data Anak</a>
								</th>
							</tr>
						    <tr>
								<th>Aksi</th>
								<th>Nama</th>
								<th>Tgl Lahir</th>
								<th>Nama Ayah/Ibu</th>	
								<th>Keterangan</th>
							</tr>	
					    </thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
			$html .='<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Anak" data-toggle="modal" data-target="#anakModalJd" data-id="'.$value->jd_dd_anak_id.'" data-nama="'.$value->nama.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-ibu="'.$value->nama_ibu.'" data-ayah="'.$value->nama_ayah.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Anak" data-toggle="modal" data-target="#hapusAnakModalJd" data-id="'.$value->jd_dd_anak_id.'"><i class="fa fa-remove"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nama.'</td>';			
			$html .='<td>'.$value->tgl_lahir.'</td>';
			$html .='<td>'.$value->nama_ayah.'/'.$value->nama_ibu.'</td>';
			$html .='<td>'.$value->keterangan.'</td>';				
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}

    public function getAnakAll()
	{
		$id			= $this->input->get('usul_id');		
		$usul 		= $this->verifikator->getAnak($id);
		
		$html = '';
		$html .='<table id="tb-anak" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th><a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#anakModal" data-usul="'.$id.'" data-id="" data-tooltip="tooltip" title="Tambah Data Anak"> <i class="fa fa-plus"></i> Data Anak</a>
								</th>
							</tr>
						    <tr>
								<th rowspan="2" style="width:100px;">Aksi</th>
								<th rowspan="2">Nama</th>
								<th rowspan="2">LK/PR</th>
								<th rowspan="2">Tgl Lahir</th>
								<th colspan="3">Keterangan Tentang Ibu/Ayah </th>								
							</tr>
							<tr>									
								<th>Nama</th>
								<th>Cerai Tgl</th>
								<th>Meninggal Tgl</th>
							</tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
			$html .='<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Anak" data-toggle="modal" data-target="#anakModal" data-id="'.$value->mutasi_id.'" data-nama="'.$value->nama.'" data-sex="'.$value->sex.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-tgl_cerai="'.$value->cerai_tgl.'" data-tgl_wafat="'.$value->meninggal_tgl.'" data-nama_ibu_ayah="'.$value->nama_ibu_ayah.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Anak" data-toggle="modal" data-target="#hapusAnakModal" data-id="'.$value->mutasi_id.'"><i class="fa fa-remove"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nama.'</td>';
			$html .='<td>'.$value->sex.'</td>';
			$html .='<td>'.$value->tgl_lahir.'</td>';
			$html .='<td>'.$value->nama_ibu_ayah.'</td>';
			$html .='<td>'.$value->cerai_tgl.'</td>';
			$html .='<td>'.$value->meninggal_tgl.'</td>';			
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
		
	
	public function getIstriAll()
	{
		$id			= $this->input->get('usul_id');
		$usul 		= $this->verifikator->getIstri($id);		
		$html = '';
		$html .='<table id="tb-istri" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th><a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#istriModal" data-usul="'.$id.'" data-id="" data-tooltip="tooltip" title="Tambah Data Istri/Suami"> <i class="fa fa-plus"></i> Data Istri/Suami</a>
								</th>
							</tr>
							<tr>
								<th style="width:100px;">Aksi</th>
								<th>Nama</th>
								<th>Nama Kecil</th>
								<th>Tempat/Tgl Lahir</th>
								<th>Tanggl Nikah</th>
								<th>Tanggal Pendaftaran</th>
								<th>Tanggal Cerai</th>
								<th>Tanggal Wafat</th>	
								<th style="width:100px;">Alamat</th>
							</tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
			$html .='<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Istri" data-toggle="modal" data-target="#istriModal" data-id="'.$value->mutasi_id.'" data-nama="'.$value->nama.'" data-nama_kecil="'.$value->nama_kecil.'" data-tempat_lahir="'.$value->tempat_lahir.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-tgl_nikah="'.$value->tgl_nikah.'" data-tgl_pendaftaran="'.$value->tgl_pendaftaran.'" data-tgl_cerai="'.$value->tgl_cerai.'" data-tgl_wafat="'.$value->tgl_wafat.'" data-alamat="'.$value->alamat.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Istri" data-toggle="modal" data-target="#hapusIstriModal" data-id="'.$value->mutasi_id.'"><i class="fa fa-remove"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nama.'</td>';
			$html .='<td>'.$value->nama_kecil.'</td>';
		    $html .='<td>'.$value->tempat_lahir.'/'.$value->tgl_lahir.'</td>';
			$html .='<td>'.$value->tgl_nikah.'</td>';
			$html .='<td>'.$value->tgl_pendaftaran.'</td>';
			$html .='<td>'.$value->tgl_cerai.'</td>';
			$html .='<td>'.$value->tgl_wafat.'</td>';
			$html .='<td>'.$value->alamat.'</td>';
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	
	public function simpanAnakJd()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');		
		$this->form_validation->set_rules('nama_ibu', 'nama_ibu', 'required');
		$this->form_validation->set_rules('nama_ayah', 'nama_ayah', 'required');
		$this->form_validation->set_rules('keterangan', 'keterangan', 'required');
		
	    $this->form_validation->set_message('required', '{field} tidak boleh kosong');

		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Silahkan Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$temp_id			 = $this->input->post('jd_dd_anak_id');
			
			if(!empty($temp_id))
			{	
				$result				 = $this->verifikator->updateAnakJd();
			}
			else
			{
				$result				 = $this->verifikator->simpanAnakJd();
			}
			
			
			
			$response   		 =  $result['response'];
			$data['pesan']       =  $result['pesan'];
			
			if($response != TRUE)
			{
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				return FALSE;	
			}
			else
			{
				$data['pesan']	= $result['pesan'];
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}		
		}
	}

	public function hapusAnakJd()
	{
		$data['result']		= $this->verifikator->hapusAnakJd();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}	
	
	public function simpanAnak()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('tgl_cerai', 'Tanggal Cerai', 'trim');
		$this->form_validation->set_rules('tgl_wafat', 'Tanggal Wafat', 'trim');
		$this->form_validation->set_rules('nama_ibu_ayah', 'nama_ibu_ayah', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Silahkan Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$temp_id			 = $this->input->post('temp_mutasi_id');
			
			if(!empty($temp_id))
			{	
				$result				 = $this->verifikator->updateAnak();
			}
			else
			{
				$result				 = $this->verifikator->simpanAnak();
			}
			
						
			$response   		 =  $result['response'];
			$data['pesan']       =  $result['pesan'];
			
			if($response != TRUE)
			{
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				return FALSE;	
			}
			else
			{
				$data['pesan']	= $result['pesan'];
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}
		
		}
	}

    public function hapusAnak()
	{
		$data['result']		= $this->verifikator->hapusAnak();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function simpanIstri()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('nama_kecil', 'Nama Kecil', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('tgl_nikah', 'Tanggal Menikah', 'required');
		$this->form_validation->set_rules('tgl_cerai', 'Tanggal Cerai', 'trim');
		$this->form_validation->set_rules('tgl_wafat', 'Tanggal Wafat', 'trim');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Silahkan Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$temp_id			 = $this->input->post('temp_mutasi_id');
			
			if(!empty($temp_id))
			{	
				$result				 = $this->verifikator->updateIstri();
			}
			else
			{
				$result				 = $this->verifikator->simpanIstri();
			}
			
			
			$response   		 =  $result['response'];
			$data['pesan']       =  $result['pesan'];
			
			if($response != TRUE)
			{
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				return FALSE;	
			}
			else
			{
				$data['pesan']	= $result['pesan'];
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}
		
		}
	}	
	
	public function hapusIstri()
	{
		$data['result']		= $this->verifikator->hapusIstri();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */