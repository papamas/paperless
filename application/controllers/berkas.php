<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Berkas extends MY_Controller {
	
	var $menu_id    = 13;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation'));
		$this->load->model('berkas/berkas_model', 'berkas');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	public function index()
	{
	   
		$data['menu']      		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']      		=  $this->auth->getName();
        $data['jabatan']   		=  $this->auth->getJabatan();
		$data['member']	   		=  $this->auth->getCreated();
		$data['avatar']	   		=  $this->auth->getAvatar();
		
		$data['instansi']  		= $this->berkas->getInstansi();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('berkas/index',$data);
	}
	
	
	public function getBerkas()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
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
			
			$data['instansi']  = $this->berkas->getInstansi();
			$data['show']  	   = FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('berkas/index',$data);
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
				$data['instansi']  		=  $this->berkas->getInstansi();
				$data['show']  			=  TRUE;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('berkas/index',$data);
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
		$filename         = "USUL BERKAS INSTANSI".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'CEK BERKAS';
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
					<th>NO</th>
					<th>NIP</th>
					<th>NAMA</th>
					<th>AGENDA</th>
					<th>TERIMA</th>
					<th>LAYANAN</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					<th>BERKAS YANG DI UPLOAD</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->nama}</td>";	
				$html .= "<td>{$r->agenda_nousul}</td>";
				$html .= "<td>{$r->agenda_timestamp}</td>";				
				$html .= "<td>{$r->layanan_nama}</td>";
				$html .= "<td>{$r->nomi_status}</td>";		
                $html .= "<td>{$r->nomi_alasan}</td>";					
				$html .= "<td>{$r->upload_dokumen}</td>";					
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
