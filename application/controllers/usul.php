<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usul extends MY_Controller {
	
	var $menu_id    = 14;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','Myencrypt'));
		$this->load->model('usul/usul_model', 'usul');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	public function index()
	{
		$data['menu']     =  $this->menu->build_menu();
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['usul']     =  $this->usul->getAll();
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('usul/index',$data);
	}
	
	public function getPdf()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:attachment; filename=pengantar.pdf');                      
		header('Expires:0'); 
		@readfile(base_url().'agenda/'.$instansi.'/'.$file);
	}	
	
	public function getInline()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename=pengantar.pdf');                      
		header('Expires:0'); 
		readfile(base_url().'agenda/'.$instansi.'/'.$file);
	}	
	
	public function getExcel()
	{
		$id  = $this->input->get('id');						
		$q    = $this->usul->getExcel($id);
		
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "DAFTAR ANTRIAN USUL BERKAS INSTANSI".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR ANTRIAN USUL BERKAS';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table><tr><td  colspan=2>TANGGAL</td><td>'.$row->agenda_timestamp.'</td></tr>';
		$html .= '<tr><td  colspan=2>NO AGENDA</td><td>'.$row->agenda_nousul.'</td></tr>';
		$html .= '<tr><td  colspan=2>INSTANSI</td><td>'.$row->instansi.'</td></tr>';
		$html .= '<tr><td  colspan=2>PELAYANAN</td><td>'.$row->layanan_nama.'</td></tr>';
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}.dt{width:450;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NO</th>
					<th>NIP</th>
					<th>GOL</th>
					<th>NAMA</th>
					<th>TAHAP</th>	
					<th>STATUS</th>	
                    <th>LEVEL 1</th> 
					<th>NOTE LEVEL 1</th>
					<th>LEVEL 2</th> 
					<th>NOTE LEVEL 2</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){									
			$i = 1;		
					
			foreach ($q->result() as $r) {			
				switch($r->tahapan_id){
					case 4:
						$n = $r->work_name;
					break;
					case 5:
						$n = $r->work_name;
					break;
					case 6:
						$n = $r->lock_name;
					break;
					case 7:
						$n = $r->verif_name_satu;
					break;
					case 8:
						$n = $r->verif_name_dua;
					break;
					case 9:
						$n = $r->verif_name_dua;
					break;
					case 10:
						$n = $r->verif_name_tiga;
					break;
					case 11:
						$n = $r->verif_name_tiga;
					break;
					case 12:
						$n = $r->entry_proses_name;
					break;
					case 13:
						$n = $r->entry_name;
					break;					
					default:
					   $n = "";
				}	
				
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";
                $html .= "<td>{$r->golongan}</td>";					
                $html .= "<td>{$r->nama}</td>";	
				$html .= "<td>{$r->tahapan_nama}".' '."{$n}</td>";
				$html .= "<td>{$r->nomi_status}</td>";	
				$html .= "<td>{$r->status_level_satu}".'<br/>'."{$r->verifdate_level_satu}</td>";
				$html .= "<td>{$r->alasan_level_satu}</td>";
				$html .= "<td>{$r->status_level_dua}".'<br/>'."{$r->verifdate_level_dua}</td>";
				$html .= "<td>{$r->alasan_level_dua}</td>";
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
