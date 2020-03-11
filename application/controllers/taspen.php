
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taspen extends MY_Controller {

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
	 
	var $menu_id    = 29;
	var $allow 		= FALSE;
 
	 
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('taspen/upload/upload_model', 'uploadFile');
		$this->load->model('taspen/berkas/berkas_model', 'berkas');
		$this->load->model('taspen/validasi/validasi_model', 'validasi');
		$this->load->model('menu_model');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	}
	
	public function index()
	{
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['show']	  =  FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/validasi/index',$data);
		
	}
	
	public function getValidasiSK()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('searchby', 'Filter', 'trim');
		$this->form_validation->set_rules('search', 'Data Pencarian', 'trim');		
		
        $perintah		  = $this->input->post('perintah');	
		
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['instansi']  		= $this->validasi->getInstansi();
			
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(29);
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/validasi/index',$data);
		}
        else
        {			
			
			$q				  = $this->validasi->getValidasiSK();
			
			if($perintah == 1) {
				
				$data['menu']    		=  $this->menu->build_menu();
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['daftar']    		=  $q;
				$data['instansi']		=  $this->validasi->getInstansi();
				
				$data['show']  			=  TRUE;
				$this->allow 			=  $this->auth->isAuthMenu(29);
				
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/validasi/index',$data);
			}
			else
			{
				$this->_getExcel($q);
				
			}
	    }	
	}	
	
	public function getValidSK()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
				
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		readfile(base_url().'uploads/'.$instansi.'/'.$file);
	}	
	
	public function doUpload()
    {
		$instansi						= $this->session->userdata('session_instansi');		
		$target_dir						='./taspen/dokumen/';		
		$config['upload_path']          = $target_dir;
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 3024;
		$config['encrypt_name']			= FALSE;	
		$config['overwrite']			= TRUE;	
		$ocnfig['detect_mime']			= TRUE;
		
		if(!is_dir($target_dir)){
			mkdir($target_dir, 0777, TRUE);
		}

		$this->load->library('upload', $config);	
		
        if(! $this->uploadFile->_is_arsip($_FILES['file']['name'])){
            $error = array('error' => 'File bukan dokumen kepegawaian');

			$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($error));
			return FALSE;			
		}			
				

		if ( ! $this->upload->do_upload('file'))
		{
				$error = array('error' => strip_tags($this->upload->display_errors()));

				$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($error));
				
		}
		else
		{
				$data 		= $this->upload->data();
				$result		= $this->uploadFile->insertUpload($data);
				
			
				if($result['response'])
				{
				    $this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result)); 
                }
				else
				{
					$result['updated']  = $this->uploadFile->updateFile($result);
					$result['error'] 	= 'File sudah ada, overwrite file';
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result));

                }			
				
		}
    }
	
	public function daftar()
	{
			
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		$this->allow 			= $this->auth->isAuthMenu(28);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/upload/daftar',$data);
		
	}	
	
	public function getDaftar()
	{
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'required');		
		
        $perintah		  = $this->input->post('perintah');	
		$daftar			  = $this->input->post();
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(28);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/upload/daftar',$data);
		}
        else
        {			
			
			$q				  = $this->uploadFile->getDaftar($daftar);
			if($perintah == 1) {
				
				$data['menu']    		=  $this->menu->build_menu();
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['daftar']    		= $q;
				$data['show']  			= TRUE;
				$this->allow 			= $this->auth->isAuthMenu(28);
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/upload/daftar',$data);
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
		$filename         = "DAFTAR DOKUMEN INSTANSI".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/x-msdownload');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR DOKUMEN INSTANSI';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';	
		$html .= '<tr><td  colspan=2>TANGGAL</td><td>'.date('d-M-Y H:i:s').'</td></tr>';
		$html .= '<tr><td  colspan=2>INSTANSI</td><td>'.$row->instansi.'</td></tr>';		
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>DOKUMEN</th>					
					<th>NIP</th>
					<th>NAMA</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				
				$html .= "<tr><td>{$r->nama_dokumen}</td>";               			
				$html .= "<td class=str>".(!empty($r->nip_lama) ? $r->nip_lama : $r->nip_baru)."</td>";	
                $html .= "<td>{$r->nama}</td>";	
				$html .= "<td>{$r->created_date}</td>";									
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
	
	

	public function getInline()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
				
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		readfile(base_url().'taspen/dokumen/'.$file);
	}	
	
	/*  cek berkas */
	public function berkas()
	{
	   
		$data['menu']      		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']      		=  $this->auth->getName();
        $data['jabatan']   		=  $this->auth->getJabatan();
		$data['member']	   		=  $this->auth->getCreated();
		$data['avatar']	   		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/berkas/index',$data);
	}
	
	public function getBerkas()
	{
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'required');
		
		$search['search']              = $this->input->post('search');
		$search['searchby']            = $this->input->post('searchby');
		$perintah           		   = $this->input->post('perintah');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']      =  $this->menu->build_menu();
			$data['lname']     =  $this->auth->getLastName();        
			$data['name']      =  $this->auth->getName();
			$data['jabatan']   =  $this->auth->getJabatan();
			$data['member']	   =  $this->auth->getCreated();
			$data['avatar']	   =  $this->auth->getAvatar();			
			
			$data['show']  	   = FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/berkas/index',$data);
		}
		else
		{	
			$q	  						   = $this->berkas->getUsulDokumen($search);
				
			if($perintah == 1)			{
				
				$data['menu']     		=  $this->menu->build_menu();
				$data['lname']    		=  $this->auth->getLastName();        
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['usul']	  		=  $q;				
				$data['show']  			=  TRUE;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/berkas/index',$data);
			}
			else
			{	
				$this->_getExcel($q);
			}
		}	
	}
	
	/* Usul*/
	public function usul()
	{
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		
		$this->load->view('taspen/usul/index',$data);
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */