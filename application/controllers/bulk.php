
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulk extends MY_Controller {

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
	var $menu_id    = 27;
	var $allow 		= FALSE;

	
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('bulk/bulk_model', 'bulk');
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
		$data['instansi'] = $this->bulk->getInstansi();
	    $data['layanan']  = $this->bulk->getLayanan();

		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('bulk/bulk',$data);
		
	}
	
	public function getPhoto()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'trim|required');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
		    $data['menu']     =  $this->menu->build_menu();
			$data['name']     =  $this->auth->getName();
			$data['jabatan']  =  $this->auth->getJabatan();
			$data['member']	  =  $this->auth->getCreated();
			$data['avatar']	  =  $this->auth->getAvatar();
			$data['instansi'] = $this->bulk->getInstansi();
			$data['layanan']  = $this->bulk->getLayanan();

			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('bulk/bulk',$data);
		}	
        else
		{		
		
			$this->load->library('PDF', array());
					
			
			$this->pdf->setPrintHeader(false);
			$this->pdf->setPrintFooter(false);	
			
			$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$this->pdf->SetAutoPageBreak(false, 5);
			$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			$this->pdf->SetFont('freeSerif', '', 4);
			
			$this->pdf->AddPage('P', 'A4');
			
			// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

			
			
			$photo		= $this->bulk->getPhoto();
			//var_dump($photo->num_rows());exit;
			$x       = 10;
			$y       = 10;
			
			$x1      = 15;
			$y1      = 5;
			
			$break   = 8;
			$counter = 0;
			
			$instansi		= $this->input->post('instansi');
			
			
			foreach ($photo->result() as $value)
			{
				$this->pdf->Image(base_url() . 'photo/'.$instansi.'/'.$value->orig_name, $x, $y, 20, 30, 'JPG', '', 'T', false, '', 'T', false, false, 0, false, false, false);
				
			    $xnama      = explode(" ",$value->PNS_PNSNAM,2);

				if(count($xnama) == 2)
				{
					$fn 	= $xnama[0];
					$ln		= $xnama[1];
				}
				else
				{
					$fn		= $xnama[0];
					$ln		= '';
				}  
				
				$counter ++;

				$this->pdf->Text($x1, $y1, $counter.".".$value->PNS_PNSNAM);
				
				
				
				if($counter % 56 === 0)
				{
					$this->pdf->AddPage('P', 'A4', false, false);
					$x       = 5;
					$y       = 10;
					
					$x1      = 10;
					$y1      = 5;
				}
				
				if($counter % $break === 0)
				{
					$x  = 10;
					$y  += 37;
									
					$x1 = 15;
					$y1 += 37;
				}
				else
				{
					$x += 25; // new column
					$x1 +=25;	
				}		
				
					
				
				//$y += 8; // new row			
			}
			ob_end_clean();
			$this->pdf->Output('bulk.pdf', 'D');
		}
	}	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */