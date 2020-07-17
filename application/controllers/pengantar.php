
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pengantar extends MY_Controller {

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
		$this->load->model('pengantar/pengantar_model', 'pengantar');
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
		$data['instansi']  		= $this->pengantar->getInstansi();
		$this->allow 			= $this->auth->isAuthMenu(9);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('pengantar/index',$data);
		
	}	
	
	public function getPengantar()
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
			
			$data['instansi']  		= $this->pengantar->getInstansi();
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(9);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('pengantar/index',$data);
		}
        else
        {			
			
			$q				        = $this->pengantar->getDaftar();			
			$data['menu']    		=  $this->menu->build_menu();
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['daftar']    		= $q;
			$data['instansi']  		= $this->pengantar->getInstansi();	
			$data['show']  			= TRUE;
			$this->allow 			= $this->auth->isAuthMenu(9);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('pengantar/index',$data);
			
			
	    }	
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