
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
	
	 
	function __construct()
    {
        parent::__construct();
		$this->load->model('dashboard/dashboard_model', 'dashboard');
	}	
	
	
	public function index()
	{
		$data['dashboard']    = $this->dashboard->getData();
		$this->load->view('dashboard/index',$data);
		
	}	
	
	public function ajaxLoad()
	{
		$dashboard    = $this->dashboard->getData();
		
		$html  ='';
		$html  .='<table class="table table-hover">';
					  	
		$i		=1;
		foreach($dashboard->result() as $value){				
			$html .='<tr>';			 
			$html .='<td class="col-md-3">'.$value->INS_NAMINS.'</td>';
			$html .='<td class="col-md-3">'.$value->layanan_nama.'</td>';
			$html .='<td class="col-md-1 center-block"><span class="label bg-maroon">'.$value->JUMLAH.'</span></td>';
			$html .='<td class="col-md-1 center-block"><span class="label label-success">'.$value->ACC.'</span></td>';
			$html .='<td class="col-md-1 center-block"><span class="label label-warning">'.$value->BTL.'</span></td>';
			$html .='<td class="col-md-1 center-block"><span class="label label-info">'.$value->BELUM.'</span></td>';
			$html .='<td class="col-md-1 center-block"><span class="label label-danger">'.$value->TMS.'</span></td>';
			$html .='<td class="col-md-1 center-block"><span class="label label-info">'.$value->update_date.'</span></td>';
			$html .='</tr>';
		}
	    $html .='</table>';
		echo $html;
	}	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */