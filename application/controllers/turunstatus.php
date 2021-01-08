
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Turunstatus extends MY_Controller {

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
	 
	var $menu_id    = 44;
	var $allow 		= FALSE;
 
	 
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('turunstatus/turunstatus_model', 'turunstatus');
		$this->load->model('menu_model');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	}	
	
	
	public function index()
	{
			
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		$data['tahapan']  		= $this->turunstatus->getTahapan();
		$this->allow 			= $this->auth->isAuthMenu(9);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('turunstatus/index',$data);
		
	}	
	
	public function getUsul()
	{
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'required|trim');		
		
        $perintah		  = $this->input->post('perintah');	
		$daftar			  = $this->input->post();
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			
			$data['tahapan']  		= $this->turunstatus->getTahapan();
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(9);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('turunstatus/index',$data);
		}
        else
        {			
			
			$q				        = $this->turunstatus->getDaftar();			
			$data['menu']    		=  $this->menu->build_menu();
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['daftar']    		= $q;
			$data['tahapan']  		= $this->turunstatus->getTahapan();
			$data['show']  			= TRUE;
			$this->allow 			= $this->auth->isAuthMenu(9);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('turunstatus/index',$data);
			
			
	    }	
	}	
	
	
	public function okTurun()
	{
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('tahapan', 'Tahapan', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['response']		= FALSE;
			$data['pesan']			= 'Lengkapi Form';					
			
			$this->output
			->set_status_header(406)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));	
		}
		else
		{		
		
			$db_debug 			= $this->db->db_debug; 
			$this->db->db_debug = FALSE; 	
			if (!$this->turunstatus->updateNominatif()) 
			{
				$error = $this->db->_error_message(); 
				
				if(!empty($error))
				{
					$data['response']		= FALSE;
					$data['pesan']			= $error;					
					
					$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));	
				}						
			}
			else
			{
				$data['response']		= TRUE;
				$data['pesan']			= 'Berhasil Menurunkan Status Usul';    
				$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data)); 				
			}	
					
			$this->db->db_debug = $db_debug; //restore
		}
	}	
	
	
	public function getTurunstatus()
	{
		$daftar				        = $this->turunstatus->getDaftar();
		$no							= 1;
		$html = '';
		$html .='<table id="tbDaftar" class="table table-striped">
					<thead>
						<tr>
							<th>NO</th>
							<th>#</th>
							<th>STATUS</th>
							<th>TAHAPAN</th>
							<th>BY DATE</th>
							<th>USUL</th>
							<th>INSTANSI</th>
							<th>NIP</th>
							<th>NAMA</th>
						</tr>
					</thead>';
		
		foreach($daftar->result() as $value)
		{
			$html .='<tr>
						<td>'.$no.'</td>
						<td><a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Turun Status" data-toggle="modal" data-target="#turunModal" data-agenda="'.$value->agenda_id.'" data-nip="'.$value->nip.'" data-alasan="'.$value->nomi_alasan.'" data-status="'.$value->nomi_status.'" data-tahapan="'.$value->tahapan_id.'"><i class="fa fa-long-arrow-down"></i></a></td> 
						<td>'.$value->nomi_status.'</td>
						<td>'.$value->tahapan_nama.'</td>
						<td>'.$value->verify_date.'<br/>'.$value->first_name.'</td>
						<td>'.$value->agenda_nousul.'</td>
						<td>'.$value->nama_instansi.'</td>	
						<td>'.$value->nip.'</td>
						<td>'.$value->nama_pns.'</td>								
					</tr>	';
			$no++;		
     	}
		
		$html .='</table>';		
        echo $html;		
	}	

	public function getInline()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$flok      = base_url().'agenda/'.$instansi.'/'.$file;
						
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		ob_end_clean();
		readfile($flok); 
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */