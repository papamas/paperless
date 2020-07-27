
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap3k extends MY_Controller {

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
	 
	var $menu_id    = 40;
	var $allow 		= FALSE;
 
	 
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('ap3k/ap3k_model', 'ap3k');
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
		$data['show']  			= TRUE;
		$data['instansi']  		= $this->ap3k->getInstansi();
		$data['daftarPengantar']= $this->ap3k->getDaftarPengantar();
		$data['nomorAgenda']    = $this->ap3k->getNomorAgenda()->row();
		$data['pesan'] ='';
		
		$this->allow 			= $this->auth->isAuthMenu(9);
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('ap3k/index',$data);
		
	}	
	
	public function savePengantar()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('nomorPengantar', 'Nomor Pengantar', 'required');
		$this->form_validation->set_rules('tanggalPengantar', 'Tanggal Pengantar', 'required');
		$this->form_validation->set_rules('nomorAgenda', 'Nomor Agenda', 'required');
		$this->form_validation->set_rules('tanggalAgenda', 'Tanggal Agenda', 'required');
		$this->form_validation->set_rules('jenisUsul', 'Jenis Usul', 'required');
		$this->form_validation->set_rules('permintaan', 'Permintaan', 'required');
		$this->form_validation->set_rules('agendaMaleo', 'agendaMaleo', 'required');
		$this->form_validation->set_rules('agendaUsulmaleo', 'agendaUsulmaleo', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan'] ='';
		}
		else
		{
			$kdPengantar		= $this->input->post('kdPengantar');
			
			if(!empty($kdPengantar))
			{					
				$result   = $this->ap3k->updatePengantar();
				if(!$result['response'] )
				{
					$data['pesan']			= '<div class="box box-warning"><div class="callout callout-warning">
									<h4>'.$result['pesan'].'!</h4></div></div>';
				}
				else
				{
					$data['pesan']			= '<div class="box box-success"><div class="callout callout-success">
									<h4>Berhasil Melakukan Update pengantar!</h4></div></div>';
				}
			}
			else
			{
				$result   = $this->ap3k->insertPengantar();
				if(!$result['response'] )
				{
					$data['pesan']			= '<div class="box box-warning"><div class="callout callout-warning">
									<h4>'.$result['pesan'].'!</h4></div></div>';
				}
				else
				{
					$data['pesan']			= '<div class="box box-success"><div class="callout callout-success">
									<h4>Berhasil Menambahkan pengantar!</h4></div></div>';
				}
			}
			
			
			
		
		}
		
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['show']  			=  TRUE;
		$data['instansi']  		= $this->ap3k->getInstansi();
		$data['nomorAgenda']    = $this->ap3k->getNomorAgenda()->row();
		$data['daftarPengantar']= $this->ap3k->getDaftarPengantar();
		$this->allow 			= $this->auth->isAuthMenu(9);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('ap3k/index',$data);
		
	}	
	
	public function hapusPengantar()
	{
		$data['result']		= $this->ap3k->hapusPengantar();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function getPengantarByName()
	{
		$query		= $this->ap3k->getPengantarByName();
		echo json_encode($query->result());
			
	}

	public function getPengantarById()
	{
		$query		= $this->ap3k->getPengantarById();
		echo json_encode($query->row());
			
	}
	
	public function nominatif()
	{
		$data['nominatif']		=  $this->ap3k->getNominatif();
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		$this->allow 			= $this->auth->isAuthMenu(9);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$data['kdPengantarAp3k'] =  $this->myencrypt->decode($this->input->get('k'));
		$this->load->view('ap3k/nominatif',$data);
    }
	
	public function saveNominatif()
	{
		$this->ap3k->saveNominatif();
		
		$data['nominatif']		=  $this->ap3k->getNominatif();
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['kdPengantarAp3k'] =  $this->input->post('kdPengantarAp3k');
		
		$this->allow 			= $this->auth->isAuthMenu(9);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('ap3k/nominatif',$data);	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */