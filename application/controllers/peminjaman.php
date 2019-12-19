
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peminjaman extends MY_Controller {

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
	public function index()
	{
		$this->load->library('Menu');
		$this->load->model('menu_model');
		
		$query 		      =  $this->menu_model->getMenu();		
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
		$data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$this->load->view('peminjaman/vpeminjaman',$data);			
		
	}
	
	function getInstansi($kode_instansi)
	{
	    $this->load->model('peminjaman_model');
		return $this->peminjaman_model->getIntansi($kode_instansi);
	}
	
	function getPns()
	{
	    $this->load->model('peminjaman_model');
		$nip    = $this->input->post('q');
		
		$query  =   $this->peminjaman_model->getPns($nip);
		$ret['results'] = $query->result_array();
	    echo json_encode($ret);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */