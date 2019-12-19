
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
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));				
		$this->load->model('menu_model');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
		
	}

	
	public function index()
	{
		
		$data['menu']     			   =  $this->menu->build_menu();
		$data['name']     			   =  $this->auth->getName();
        $data['jabatan']  			   =  $this->auth->getJabatan();
		$data['member']	  			   =  $this->auth->getCreated();
		$data['avatar']	  			   =  $this->auth->getAvatar();
		
		$search['search']              = NULL;
		$search['searchby']            = NULL;
		
		$data['usul']	  = $this->verifikator->getUsulDokumen($search);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('verifikator/index',$data);
		
	}
	
	public function find()
	{
		$this->form_validation->set_rules('search', 'search', 'required');
		$this->form_validation->set_rules('searchby', 'searchby', 'required');
		
		
		$data['menu']     				=  $this->menu->build_menu();	
		$data['name']     				=  $this->auth->getName();
        $data['jabatan']  				=  $this->auth->getJabatan();
		$data['member']	  				=  $this->auth->getCreated();
		$data['avatar']	  				=  $this->auth->getAvatar();
		
		
		$search['search']              	=  $this->input->post('search');
		$search['searchby']            	=  $this->input->post('searchby');
		
		$data['usul']	  				= $this->verifikator->getUsulDokumen($search);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		
		$this->load->view('verifikator/index',$data);
		
	}
	
	public function verifyGet()
	{
		$data['id_agenda'] 	=  $this->myencrypt->decode($this->input->get('i'));
		$data['nip'] 		=  $this->myencrypt->decode($this->input->get('n'));
		$data['layanan_id'] =  $this->myencrypt->decode($this->input->get('p'));
		
		
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
			$data['response']	    = $this->verifikator->setVerifikator($data);
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
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
	
	    $this->form_validation->set_rules('instansi', 'instansi', 'required');
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
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('verifikator/kinerja',$data);
		}
        else
        {			
			$q                = $this->verifikator->getKinerja($this->input->post());	
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
					$data['usul']  		  = $q;
					$data['show']		  = TRUE;
					if(!$this->allow)
					{
						$this->load->view('403/index',$data);
						return;
					}
					$this->load->view('verifikator/kinerja',$data);
			}
			else
			{	
				$this->_getExcel($q,$data);
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
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */