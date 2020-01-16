
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload extends MY_Controller {

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
	 
	var $menu_id    = 8;
	var $allow 		= FALSE;
 
	 
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('upload/upload_model', 'uploadFile');
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
		$this->load->view('upload/index',$data);
		
	}
	
	public function doUpload()
    {
		$instansi						= $this->session->userdata('session_instansi');		
		$target_dir						='./uploads/'.$instansi;		
		$config['upload_path']          = $target_dir;
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 3024;
		$config['encrypt_name']			= FALSE;	
		$config['overwrite']			= TRUE;	
		$ocnfig['detect_mime']			= TRUE;
		
		if(!file_exists($target_dir)){
			mkdir($target_dir,0777);
		}

		$this->load->library('upload', $config);	
		
        if(! $this->uploadFile->_is_arsip($_FILES['file']['name'])){
            $error = array('error' => 'File ini tidak diperbolehkan untuk diupload');

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
					$result['error'] 	= 'File dokumen kepegawaian sudah ada, overwrite file';
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
		$data['instansi']  		= $this->uploadFile->getInstansi();
		$this->allow 			= $this->auth->isAuthMenu(9);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('upload/daftar',$data);
		
	}	
	
	public function getDaftar()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
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
			
			$data['instansi']  		= $this->uploadFile->getInstansi();
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(9);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('upload/daftar',$data);
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
				$this->allow 			= $this->auth->isAuthMenu(9);
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('upload/daftar',$data);
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
		header('Content-type:application/vnd.ms-excel');
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
					
					<th>SK</th>
					<th>INSTANSI</th>
					<th>NIP</th>
					<th>NAMA</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$jenis_sk     = $r->nama_dokumen;
														
				if($jenis_sk != "IJAZAH" && $jenis_sk != "TRANSKRIP" && $jenis_sk != "IBEL" && $jenis_sk != "MOU")
				{
					switch($r->minor_dok){
						case 45:
							$n = "IV/e";
						break;
						case 44:
							$n = "IV/d";
						break;
						case 43:
							$n = "IV/c";
						break;
						case 42:
							$n = "IV/b";
						break;
						case 41:
							$n = "IV/a";
						break;
						case 34:
							$n = "III/d";
						break;
						case 33:
							$n = "III/c";
						break;
						case 32:
							$n = "III/b";
						break;
						case 31:
							$n = "III/a";
						break;
						case 24:
							$n = "II/d";
						break;
						case 23:
							$n = "II/c";
						break;
						case 22:
							$n = "II/b";
						break;
						case 21:
							$n = "II/a";
						break;
						case 14:
							$n = "I/d";
						break;
						case 13:
							$n = "I/c";
						break;
						case 12:
							$n = "I/b";
						break;
						case 11:
							$n = "I/a";
						break;
						default:
							$n = $r->minor_dok;									
																	
					}	
				}
				else
				{
					
					switch($r->minor_dok){
						case 50:
							$n = "S-3/Doktor";
						break;
						case 45:
							$n = "S-2";
						break;
						case 40:
							$n = "S-1/Sarjana";
						break;
						case 35:
							$n = "Diploma IV";
						break;
						case 30:
							$n = "Diploma III/Sarjana Muda";
						break;
						case 25:
							$n = "Diploma II";
						break;
						case 20:
							$n = "Diploma I";
						break;
						case 18:
							$n = "SLTA Keguruan";
						break;
						case 17:
							$n = "SLTA Kejuruan";
						break;
						case 15:
							$n = "SLTA";
						break;
						case 12:
							$n = "SLTP Kejuruan";
						break;
						case 10:
							$n = "SLTP";
						break;
						case 15:
							$n = "Sekolah Dasar";
						break;														
						default:
							$n = $r->minor_dok;									
																	
					}								
						
				}
				
				$html .= "<tr><td>".$r->nama_dokumen." ".$n."</td>";		
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

	public function getInline()
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */