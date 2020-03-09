
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo extends MY_Controller {

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
	var $menu_id    = 10;
	var $allow 		= FALSE;

	
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('photo/photo_model', 'uploadFile');
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
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('photo/index',$data);
		
	}
	
	public function doUpload()
    {
		$instansi						= $this->session->userdata('session_instansi');		
		$target_dir						='./photo/'.$instansi;		
		$config['upload_path']          = $target_dir;
		$config['allowed_types']        = 'jpeg|jpg';
		$config['max_size']             = 1024;
		$config['encrypt_name']			= FALSE;	
		$config['overwrite']			= TRUE;	
		$config['detect_mime']			= TRUE;
		//$config['max_width']			= 164;
		//$config['max_height']			= 244;	
 
		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, TRUE);
		}
		

		$this->load->library('upload', $config);	
		
		if(! $this->uploadFile->_is_photo($_FILES['file']['name'])){
            $error = array('error' => 'File bukan photo yang diizinkan');

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
				
				$this->resizeImage($instansi,$data);
			
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
					$result['error'] 	= 'Photo sudah ada, overwrite file';
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result));

                }			
				
		}
    }
	
	public function resizeImage($instansi,$data)
    {	  
	    $source_path = $data['full_path'];
        $target_path = $data['full_path'];	  
	  
        //Compress Image
		$config['image_library']		= 'gd2';
		$config['source_image']			= $source_path;
		$config['create_thumb']			= FALSE;
		$config['maintain_ratio']		= FALSE;
		$config['width']				= 128;
		$config['height']				= 150;
		$config['new_image']			=  $target_path;
		$config['quality']				= '100%';
		$this->load->library('image_lib', $config);
		
        if (!$this->image_lib->resize()) {
            $error = array('error' => strip_tags($this->image_lib->display_errors()));
			$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($error));
        }
        $this->image_lib->clear();
    }
	
	public function daftar()
	{
			
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		$data['instansi']  		= $this->uploadFile->getInstansi();
		$this->allow 			= $this->auth->isAuthMenu(11);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('photo/daftar',$data);
		
	}	
	
	public function getDaftar()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('searchby', 'Filter', 'trim');
		$this->form_validation->set_rules('search', 'Data', 'trim');		
		
        $perintah		  = $this->input->post('perintah');	
		$daftar			  = $this->input->post();
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			
			$data['instansi']  		= $this->uploadFile->getInstansi();
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(11);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('photo/daftar',$data);
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
				$data['instansi']  		= $this->uploadFile->getInstansi();	
				$data['show']  			= TRUE;
				$this->allow 			= $this->auth->isAuthMenu(11);
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('photo/daftar',$data);
			}
			else
			{
				$this->_getExcel($q);
				
			}
	    }	
	}	
	
	public function getInline()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$size      = $this->myencrypt->decode($this->input->get('s'));
		$nip       = $this->myencrypt->decode($this->input->get('n'));
		
		ob_clean();		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:image/jpeg');
		header('Content-Disposition:inline; filename='.$nip.'.jpeg');  
		readfile(base_url().'photo/'.$instansi.'/'.$file);
	}	
	
	public function getPhoto()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$nip       = $this->myencrypt->decode($this->input->get('n'));
		
		ob_clean();		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:image/jpeg');
		header('Content-Disposition:attachment; filename='.$nip.'.jpeg');  
		readfile(base_url().'photo/'.$instansi.'/'.$file);
	}	
	
	private function _getExcel($q)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "DAFTAR PHOTO INSTANSI".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR PHOTO INSTANSI';
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
					<th>INSTANSI</th>
					<th>NIP</th>
					<th>NAMA</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				
				$html .= "<td>{$r->instansi}</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
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

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */