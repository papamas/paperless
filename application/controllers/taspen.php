
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taspen extends MY_Controller {

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
	 
	var $menu_id    = 29;
	var $allow 		= FALSE;
 
	 
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation','Telegram'));
		$this->load->model('taspen/upload/upload_model', 'uploadFile');
		$this->load->model('taspen/berkas/berkas_model', 'berkas');
		$this->load->model('taspen/validasi/validasi_model', 'validasi');
		$this->load->model('taspen/usul/usul_model', 'usul');
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
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['show']	  =  FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/validasi/index',$data);
		
	}
	
	public function getValidasiSK()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('searchby', 'Filter', 'trim');
		$this->form_validation->set_rules('search', 'Data Pencarian', 'trim');		
		
        $perintah		  = $this->input->post('perintah');	
		
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['instansi']  		= $this->validasi->getInstansi();
			
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(29);
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/validasi/index',$data);
		}
        else
        {			
			
			$q				  = $this->validasi->getValidasiSK();
			
			if($perintah == 1) {
				
				$data['menu']    		=  $this->menu->build_menu();
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['daftar']    		=  $q;
				$data['instansi']		=  $this->validasi->getInstansi();
				
				$data['show']  			=  TRUE;
				$this->allow 			=  $this->auth->isAuthMenu(29);
				
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/validasi/index',$data);
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
		header('Content-type:application/x-msdownload');
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
					<th>DOKUMEN</th>					
					<th>NIP</th>
					<th>NAMA</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {				
				$html .= "<tr><td>{$r->nama_dokumen}</td>";               			
				$html .= "<td class=str>".(!empty($r->nip_lama) ? $r->nip_lama.'/'.$r->nip_baru : $r->nip_baru)."</td>";	
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
	
	public function getValidSK()
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
	
	
	public function upload()
	{
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['dokumen']   =  $this->uploadFile->getDokumen();
		$data['show']	  =  FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/upload/index',$data);
		
	}
	
	public function doUpload()
    {
		$this->form_validation->set_rules('jenis', 'Jenis Dokumen', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			
		}
		else
		{	
			$jenis						= $this->input->post('jenis');
			$nip						= $this->input->post('nip');
			
			switch($jenis){
				case 1:
					$name  = 'SURAT_NIKAH_'.$nip;				
				break;
				case 2:
					$name  = 'SPTB_'.$nip;				
				break;
				case 3:
					$name  = 'SUKET_KEMATIAN_'.$nip;				
				break;
				case 4:
					$name  = 'SUKET_JANDA_DUDA_'.$nip;				
				break;
				case 5:
					$name  = 'PHOTO_'.$nip;				
				break;
				case 6:
					$name  = 'SP4B_'.$nip;				
				break;
				case 7:
					$name  = 'SK_PENSIUN_'.$nip;				
				break;
				case 8:
					$name  = 'AKTA_ANAK_'.$nip;				
				break;
				case 9:
					$name  = 'SUKET_KEMATIAN_CERAI_'.$nip;				
				break;
				case 10:
					$name  = 'BINTANG_JASA_'.$nip;				
				break;
				case 11:
					$name  = 'SUKET_MENETAP_'.$nip;				
				break;
				case 12:
					$name  = 'USUL_PK_'.$nip;				
				break;
				case 13:
					$name  = 'USUL_JD_'.$nip;				
				break;
				case 14:
					$name  = 'SK_JD_'.$nip;				
				break;
				case 15:
					$name  = 'SK_PK_'.$nip;				
				break;
				case 16:
					$name  = 'SUKET_ANAK_'.$nip;				
				break;
				case 17:
					$name  = 'SURAT_PERWALIAN_'.$nip;				
				break;
				case 18:
					$name  = 'USUL_YP_'.$nip;				
				break;
				case 19:
					$name  = 'SK_YP_'.$nip;				
				break;
				case 20:
					$name  = 'FORMULIR_MUTASI_KELUARGA_'.$nip;				
				break;
			}
			
			
			$target_dir						= './uploads/taspen';			
			$config['upload_path']          = $target_dir;			
			$config['max_size']             = 3024;
			$config['encrypt_name']			= FALSE;	
			$config['overwrite']			= TRUE;	
			$config['detect_mime']			= TRUE;
			$config['file_name']            = $name;
			
			
			if(!is_dir($target_dir)){
				mkdir($target_dir, 0777, TRUE);
			}
			
			
			if($jenis == 5 )
			{
				$config['allowed_types']        = 'jpg|JPG|JPEG|jpeg';
			}
			else
			{
				$config['allowed_types']        = 'pdf';
			} 
				
			$this->load->library('upload', $config);	
			$this->upload->display_errors('', '');

			if ( ! $this->upload->do_upload('file'))
			{
				$data['error']	= $this->upload->display_errors();				
			}
			else
			{
				$data 			= $this->upload->data();
				$is_image       = $data['is_image'];
							
				if($is_image === TRUE)
                {
                    $this->resizeImage($data);
                }	
                
				$result         = $this->uploadFile->insertUpload($data);
				
				
				$data['upload'] = $data;
                $response       = $result['response'];
				
				if($response === TRUE)
                {					
				    $h ='<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>'.$result['pesan'].'</div>';
						$data['pesan']	= $h;			
				}
				else
				{
                   $h ='<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>File Dokumen telah ada, overwrite file</div>';
					$data['pesan']	= $h;
				     $this->uploadFile->updateFile($data);
				}                		
			}
        }
		
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['dokumen']  =  $this->usul->getDokumen();
		
		$this->load->view('taspen/upload/index',$data);
	}	
	
	public function resizeImage($data)
    {	  
	    $source_path = $data['full_path'];
        $target_path = $data['full_path'];	  
	  
        //Compress Image
		$config['image_library']		= 'gd2';
		$config['source_image']			= $source_path;
		$config['create_thumb']			= FALSE;
		$config['maintain_ratio']		= FALSE;
		$config['width']				= 300;
		$config['height']				= 450;
		$config['new_image']			=  $target_path;
		$config['quality']				= '100%';
		$this->load->library('image_lib', $config);
		
        if (!$this->image_lib->resize()) {
            $error = array('error' => $this->image_lib->display_errors());
			
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
		$this->allow 			= $this->auth->isAuthMenu(28);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/upload/daftar',$data);
		
	}	
	
	public function getDaftar()
	{
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
			
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(28);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/upload/daftar',$data);
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
				$data['show']  			= TRUE;
				$this->allow 			= $this->auth->isAuthMenu(28);
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/upload/daftar',$data);
			}
			else
			{
				$this->_getExcelDokumen($q);
				
			}
	    }	
	}	
	
	private function _getExcelDokumen($q)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "DAFTAR DOKUMEN TASPEN ".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/x-msdownload');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR DOKUMEN TASPEN';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';	
		$html .= '<tr><td  colspan=2>TANGGAL</td><td>'.date('d-M-Y H:i:s').'</td></tr>';
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>DOKUMEN</th>					
					<th>NIP</th>
					<th>KETERANGAN</th>
					<th>BY</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {				
				$html .= "<tr><td>{$r->nama_dokumen}</td>";               			
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->keterangan}</td>";	
				$html .= "<td>{$r->upload_name}</td>";
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
	
	/* Usul Janda/Duda*/
	public function usul()
	{
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayananSK();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul(1);
		$data['golongan']  =  $this->usul->getGolongan();
		$data['temp_anak'] =  $this->usul->getTempAnakJd(20);
		$data['show'] 	   =  FALSE;
		$this->load->view('taspen/usul/index',$data);
	}	
	
	public function saveUsul()
	{
		$rules = array(
			array(
				'field' => 'usul_id',
				'label' => 'Usul Id',
				'rules' => 'trim'
			),		   
		    array(
				'field' => 'nomor_usul',
				'label' => 'Nomor Usul',
				'rules' => 'required'
			),
		    array(
				'field' => 'tgl_usul',
				'label' => 'Tanggal Usul',
				'rules' => 'required'
			),
		    array(
				'field' => 'layanan_id',
				'label' => 'Pelayanan',
				'rules' => 'trim|required'
			),
		    array(
				'field' => 'nama_janda_duda',
				'label' => 'Nama Janda/Duda',
				'rules' => 'required'
			),
		    array(
				'field' => 'nama_pns',
				'label' => 'Nama PNS',
				'rules' => 'required'
			),
		    array(
				'field' => 'nopen',
				'label' => 'NOPEN / No. Dosir',
				'rules' => 'required'
			),
		    array(
				'field' => 'nip',
				'label' => 'NIP / NRP / NVP',
				'rules' => 'required'
			),
		    array(
				'field' => 'golongan',
				'label' => 'Pangkat/Golongan',
				'rules' => 'required'
			),
		    array(
				'field' => 'jabatan',
				'label' => 'Jabatan',
				'rules' => 'required'
			),
			array(
				'field' => 'unit_kerja',
				'label' => 'Unit Kerja Terakhir',
				'rules' => 'required'
			),
			array(
				'field' => 'tgl_perkawinan',
				'label' => 'Tanggal Perkawinan',
				'rules' => 'required'
			),
			array(
				'field' => 'alamat',
				'label' => 'Alamat Ybs',
				'rules' => 'required'
			),
			array(
				'field' => 'pensiun_pokok_terakhir',
				'label' => 'Pensiun Pokok Terakhir',
				'rules' => 'required'
			),
			array(
				'field' => 'gaji_pokok_terakhir',
				'label' => 'Gaji Pokok Terakhir',
				'rules' => 'required'
			),
			array(
				'field' => 'meninggal_dunia',
				'label' => 'Tanggal Meninggal Dunia',
				'rules' => 'required'
			)				
		);
		
	    $this->form_validation->set_rules($rules);
		$this->form_validation->set_message('required', 'tidak boleh kosong');
		$this->form_validation->set_error_delimiters('', '');


		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= 'Silahkan lengkapi Form terlebih dahulu';	
			$data['tipe']	= 'warning';	
			$data['title']	= 'WARNING!';
		}
		else
		{
			$target_dir						= './uploads/taspen';			
			$config['upload_path']          = $target_dir;			
			$config['max_size']             = 3024;
			$config['encrypt_name']			= FALSE;	
			$config['overwrite']			= TRUE;	
			$config['detect_mime']			= TRUE;
			$config['file_name']            = 'PENGANTAR_'.$this->input->post('nip');
			$config['allowed_types']        = 'pdf';
			
			$this->load->library('upload', $config);	
			$this->upload->display_errors('', '');
			
			$data['nomor_usul']      = $this->input->post('nomor_usul');
			$data['tgl_usul']        = date('Y-m-d',strtotime($this->input->post('tgl_usul')));
			$data['layanan_id']      = $this->input->post('layanan_id');
			$data['nama_janda_duda'] = $this->input->post('nama_janda_duda');
			$data['nama_pns']        = $this->input->post('nama_pns');
			$data['nopen']			 = $this->input->post('nopen');
			$data['nip']             = $this->input->post('nip');
			$data['golongan']        = $this->input->post('golongan');
			$data['jabatan']         = $this->input->post('jabatan');
			$data['unit_kerja']      = $this->input->post('unit_kerja');
			$data['tgl_perkawinan']  = date('Y-m-d',strtotime($this->input->post('tgl_perkawinan')));
			$data['meninggal_dunia'] = date('Y-m-d',strtotime($this->input->post('meninggal_dunia')));
			$data['gaji_pokok_terakhir'] 	= $this->input->post('gaji_pokok_terakhir');
			$data['pensiun_pokok_terakhir'] = $this->input->post('pensiun_pokok_terakhir');
			$data['alamat'] 				= $this->input->post('alamat');
			$usul_id                 	 	= $this->input->post('usul_id');
			
			if(!empty($usul_id))					
			{
				// jika update
				$data['usul_id'] = $usul_id;
				if($_FILES['file']['name'] != NULL)
				{
					// jika file di update harus validasi dulu
					if ( ! $this->upload->do_upload('file'))
					{
						$data['error']	= $this->upload->display_errors();	
						$data['pesan']	= 'Ada yang salah pada surat pengantar';	
						$data['tipe']	= 'warning';	
						$data['title']	= 'WARNING!';
					}
					else
					{
						$upload 			     = $this->upload->data();	
						$data['file_pengantar']  = $upload['file_name'];
	                      
	                    // update usul
						$result         		 = $this->usul->updateUsul($data);	
						$response        		 = $result['response'];
					
						if($response === TRUE)
						{
							$data['pesan']	= $result['pesan'];	
							$data['tipe']	= 'success';	
							$data['title']	= 'SUCCESS!';
							
						}
						else
						{
							$data['pesan']	= $result['pesan'];	
							$data['tipe']	= 'error';	
							$data['title']	= 'ERROR!';
						}	
					}	
                }				
				else
				{
					// update usul tanpa update nama file
					$result          		 = $this->usul->updateUsul($data);
					$response        		 = $result['response'];
					
					if($response === TRUE)
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'success';	
						$data['title']	= 'SUCCESS!';
						
					}
					else
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'error';	
						$data['title']	= 'ERROR!';
					}		
				    
				}
				
			}
			else
			{					
				if ( ! $this->upload->do_upload('file'))
				{
					$data['error']	= $this->upload->display_errors();	
					$data['pesan']	= 'Ada yang salah pada surat pengantar';	
					$data['tipe']	= 'warning';	
					$data['title']	= 'WARNING!';
				}
				else
				{	
					$upload 			     = $this->upload->data();	
					$data['file_pengantar']  = $upload['file_name'];
					$result          		 = $this->usul->saveUsul($data);
					
					$response        		 = $result['response'];
					
					if($response === TRUE)
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'success';	
						$data['title']	= 'SUCCESS!';
						
					}
					else
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'error';	
						$data['title']	= 'ERROR!';
					}	
				}
			}	
		}
		
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayananSK();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul(1);
		$data['golongan']  =  $this->usul->getGolongan();
		$data['show'] 	   =  TRUE;		
		$this->load->view('taspen/usul/index',$data);
		
	}	
	
	
	public function usulmk()
	{
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayananMutasi();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul(2);		
		$data['show'] 	   =  FALSE;
		$this->load->view('taspen/usul/mutasi_keluarga',$data);
	}	
	
	public function saveUsulmk()
	{
		$this->form_validation->set_rules('usul_id', 'Usul Id', 'trim');
		$this->form_validation->set_rules('nomor_usul', 'Nomor Usul', 'trim|required');	
		$this->form_validation->set_rules('tgl_usul', 'Tanggal Usul', 'trim|required');
		$this->form_validation->set_rules('layanan_id', 'Pelayanan', 'trim|required');
		$this->form_validation->set_rules('nama_pns', 'Nama PNS', 'required');
		$this->form_validation->set_rules('nama_kecil', 'Nama Kecil', 'required');		
		$this->form_validation->set_rules('nopen', 'NOPEN / No. Dosir', 'required');
		$this->form_validation->set_rules('nip', 'NIP / NRP / NVP', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('nomor_skep', 'Nomor Surat Keputusan Pensiun', 'required');
		$this->form_validation->set_rules('tgl_skep', 'Tanggal Surat Keputusan Pensiun', 'required');
		$this->form_validation->set_rules('pensiun_pokok', 'Pensiun Pokok', 'required');
		$this->form_validation->set_rules('pensiun_tmt', 'Pensiun TMT', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat Ybs', 'required'); 
		
		$this->form_validation->set_message('required', 'tidak boleh kosong');
		$this->form_validation->set_error_delimiters('', '');
				
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= 'Silahkan lengkapi Form terlebih dahulu';	
			$data['tipe']	= 'warning';	
			$data['title']	= 'WARNING!';
		}
		else
		{
			$target_dir						= './uploads/taspen';			
			$config['upload_path']          = $target_dir;			
			$config['max_size']             = 3024;
			$config['encrypt_name']			= FALSE;	
			$config['overwrite']			= TRUE;	
			$config['detect_mime']			= TRUE;
			$config['file_name']            = 'PENGANTAR_'.$this->input->post('nip');
			$config['allowed_types']        = 'pdf';
			
			$this->load->library('upload', $config);	
			$this->upload->display_errors('', '');
			
			
			$data['nomor_usul']      = $this->input->post('nomor_usul');
			$data['tgl_usul']        = date('Y-m-d',strtotime($this->input->post('tgl_usul')));
			$data['layanan_id']      = $this->input->post('layanan_id');
			$data['nama_kecil'] 	 = $this->input->post('nama_kecil');
			$data['nama_pns']        = $this->input->post('nama_pns');			
			$data['tempat_lahir']    = $this->input->post('tempat_lahir');
			$data['tgl_lahir']       = date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
			$data['nomor_skep']      = $this->input->post('nomor_skep');
			$data['tgl_skep']    	 = date('Y-m-d',strtotime($this->input->post('tgl_skep')));			
			$data['nopen']			 = $this->input->post('nopen');
			$data['nip']             = $this->input->post('nip');
			$data['pensiun_pokok'] 	 = $this->input->post('pensiun_pokok');
			$data['pensiun_tmt']     = date('Y-m-d',strtotime($this->input->post('pensiun_tmt')));			
			$data['alamat'] 		 = $this->input->post('alamat');
			$usul_id                 = $this->input->post('usul_id');
			
			if(!empty($usul_id))					
			{
				// jika update
				$data['usul_id'] = $usul_id;
				if($_FILES['file']['name'] != NULL)
				{
					// jika file di update harus validasi dulu
					if ( ! $this->upload->do_upload('file'))
					{
						$data['error']	= $this->upload->display_errors();
						$data['pesan']	= 'Ada yang salah pada surat pengantar';	
						$data['tipe']	= 'warning';	
						$data['title']	= 'WARNING!';
					}
					else
					{
						$upload 			     = $this->upload->data();	
						$data['file_pengantar']  = $upload['file_name'];
	                      
	                    // update usul
						$result         		 = $this->usul->updateUsul($data);	
						$response        		 = $result['response'];
					
						if($response === TRUE)
						{
							$data['pesan']	= $result['pesan'];	
							$data['tipe']	= 'success';	
							$data['title']	= 'SUCCESS!';
							
						}
						else
						{
							$data['pesan']	= $result['pesan'];	
							$data['tipe']	= 'error';	
							$data['title']	= 'ERROR!';
						}	
					}	
                }				
				else
				{
					// update usul tanpa update nama file
					$result          		 = $this->usul->updateUsul($data);
					$response        		 = $result['response'];
					
					if($response === TRUE)
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'success';	
						$data['title']	= 'SUCCESS!';
						
					}
					else
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'error';	
						$data['title']	= 'ERROR!';
					}	
				    
				}
				
			}
			else
			{					
				if ( ! $this->upload->do_upload('file'))
				{
					$data['error']	= $this->upload->display_errors();	
					$data['pesan']	= 'Ada yang salah pada surat pengantar';	
					$data['tipe']	= 'warning';	
					$data['title']	= 'WARNING!';
				}
				else
				{	
					$upload 			     = $this->upload->data();	
					$data['file_pengantar']  = $upload['file_name'];
					$result          		 = $this->usul->saveUsul($data);
					
					$response        		 = $result['response'];
					
					if($response === TRUE)
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'success';	
						$data['title']	= 'SUCCESS!';
						
					}
					else
					{
						$data['pesan']	= $result['pesan'];	
						$data['tipe']	= 'error';	
						$data['title']	= 'ERROR!';
					}	
				}
			}	
		    
		}	

		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayananMutasi();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul(2);		
		$data['show'] 	   = TRUE;
		$this->load->view('taspen/usul/mutasi_keluarga',$data);
	}	
	
	public function simpanTempIstri()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('nama_kecil', 'Nama Kecil', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('tgl_nikah', 'Tanggal Menikah', 'required');
		$this->form_validation->set_rules('tgl_cerai', 'Tanggal Cerai', 'trim');
		$this->form_validation->set_rules('tgl_wafat', 'Tanggal Wafat', 'trim');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Silahkan Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$temp_id			 = $this->input->post('temp_mutasi_id');
			
			if(!empty($temp_id))
			{	
				$result				 = $this->usul->updateTempIstri();
			}
			else
			{
				$result				 = $this->usul->simpanTempIstri();
			}
			
			$response   		 =  $result['response'];
			$data['pesan']       =  $result['pesan'];
			
			if($response != TRUE)
			{
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				return FALSE;	
			}
			else
			{
				$data['pesan']	= $result['pesan'];
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}
		
		}
	}	
	
	public function getUsul()
	{
		$this->form_validation->set_rules('find', 'find', 'trim');	
		
		if($this->form_validation->run() == FALSE)
		{
			$data['form_error']      =   validation_errors();

		}
		else
		{
		
		}
		
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayanan();
		$data['dokumen']   =  $this->usul->getDokumen();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul(1);
		$data['golongan']  =  $this->usul->getGolongan();

		$this->load->view('taspen/usul/index',$data);
	}	
	
	public function kirim()
	{
		$usul_id			  = $this->input->post('usul_id');
		$nip                  = $this->input->post('usul_nip');	
		$layanan_id           = $this->input->post('usul_layanan');	
		
		$cek                  = $this->usul->cekDokumen($usul_id,$layanan_id,$nip);
		
		if(!$cek)
		{
			$data['pesan']		= 'Berkas Gagal dikirim ke BKN, belum ada dokumen usul';
			
			$this->output
			->set_status_header(406)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));	

		}
		else
		{	
		
			$this->db->trans_begin();
			$data['response']	= $this->usul->setKirim();
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				
				$data['pesan']		= 'Berkas Gagal dikirim ke BKN';
				
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			}
			else
			{
				$data['usul_id']	  = $this->input->post('usul_id');
				$data['nip']          = $this->input->post('usul_nip');	
				$data['pesantg']	  = ' Usul berkas TASPEN baru  ';
				$data['tahapan_id']   = '1';
				
				// send notifikasi to  telegram			
				$this->send_to_Telegram($data);
				
				$this->db->trans_commit();
				$data['pesan']		= 'Berkas berhasil dikirim ke BKN';				
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				
			}	
		
		}
	}
	
	public function getUsulAll()
	{
		$usul 		= $this->usul->getUsul(1);		
		$html = '';
		$html .='<table id="tb-usul" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th></th>
								<th>NOMOR</th>									
								<th>TGL USUL</th>
								<th>NIP</th>
								<th>NAMA PNS</th>
								<th>NAMA</th>
								<th>PELAYANAN</th>
								<th>FILE</th>
								<th>SYSDATE</th>
						    </tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
            $html .='<a href="#edit" class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Usul" data-nomor="'.$value->nomor_usul.'" data-tgl="'.$value->tgl.'" data-layanan="'.$value->layanan_id.'" data-nama="'.$value->nama_pns.'" data-jd="'.$value->nama_janda_duda.'" data-nopen="'.$value->nopen.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'" data-golongan="'.$value->golongan.'" data-jabatan="'.$value->jabatan.'" data-unit="'.$value->unit_kerja.'" data-perkawinan="'.$value->perkawinan.'" data-meninggal="'.$value->meninggal.'" data-gapok="'.$value->gaji_pokok_terakhir.'" data-penpok="'.$value->pensiun_pokok_terakhir.'" data-alamat="'.$value->alamat.'"><i class="fa fa-edit"></i></a>';
            $html .='&nbsp;<a href="#tampil" class="btn btn-success btn-flat btn-xs" data-usul="'.$value->usul_id.'" data-tooltip="tooltip"  title="Klik disini untuk menampilkan data Anak Kandung"><i class="fa fa-refresh"></i></a>';
			$html .='&nbsp;<a href="#" class="btn btn-danger btn-flat btn-xs" data-toggle="modal" data-target="#anakModal" data-tooltip="tooltip"  title="Tambah data Anak Kandung" data-usul="'.$value->usul_id.'"><i class="fa fa-child"></i></a>';
			$html .='&nbsp;<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama).'"><i class="fa fa-search"></i></a>';
			$html .='&nbsp;<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Usul BKN" data-toggle="modal" data-target="#kirimModal" data-nip="'.$value->nip.'" data-usul="'.$value->usul_id.'" data-layanan="'.$value->layanan_id.'" ><i class="fa fa-mail-forward"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nomor_usul.'</td>';
			$html .='<td>'.$value->tgl.'</td>';
			$html .='<td>'.$value->nip.'</td>';
			$html .='<td>'.$value->nama_pns.'</td>';
			$html .='<td>'.$value->nama_janda_duda.'</td>';
			$html .='<td>'.$value->layanan_nama.'</td>';
			$html .='<td>';
			if(!empty($value->file_pengantar))
			{
				$file = $value->file_pengantar;				
				$html .= '<span data-toggle="tooltip" data-original-title="Ada File Pengantar">
				<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?t='.$this->myencrypt->encode("application/pdf").'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
			}
			else
			{
				$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Pengantar">
				<i class="fa fa-file-o" style="color:red;"></i></span>';
			}
			$html .='</td>';
			$html .='<td>'.$value->created_date.'</td>';
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	
	public function getUsulAllmk()
	{
		$usul 		= $this->usul->getUsul(2);		
		$html = '';
		$html .='<table id="tb-usul" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th></th>
								<th>NOMOR</th>									
								<th>TGL USUL</th>
								<th>NIP</th>
								<th>NAMA PNS</th>
								<th>PELAYANAN</th>
								<th>FILE</th>
								<th>ISTRI</th>
								<th>ANAK</th>
								<th>SYSDATE</th>
						    </tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
            $html .='<a href="#edit" class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Usul" data-nomor="'.$value->nomor_usul.'" data-tgl="'.$value->tgl.'" data-layanan="'.$value->layanan_id.'" data-nama="'.$value->nama_pns.'" data-jd="'.$value->nama_janda_duda.'" data-nopen="'.$value->nopen.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'" data-tempat_lahir="'.$value->tempat_lahir.'" data-tgl_lahir="'.$value->atgl_lahir.'" data-nomor_skep="'.$value->nomor_skep.'" data-tgl_skep="'.$value->atgl_skep.'" data-penpok="'.$value->pensiun_pokok.'" data-pensiun_tmt="'.$value->apensiun_tmt.'" data-alamat="'.$value->alamat.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a href="#tampil" class="btn btn-success btn-flat btn-xs" data-usul="'.$value->usul_id.'" data-tooltip="tooltip"  title="Klik disini untuk menampilkan data Istri dan Anak"><i class="fa fa-refresh"></i></a>';
			$html .='&nbsp;<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama).'"><i class="fa fa-search"></i></a>';
			$html .='&nbsp;<a href="#" class="btn btn-success btn-flat btn-xs" data-toggle="modal" data-target="#istriModal" data-tooltip="tooltip"  title="Tambah data Istri" data-usul="'.$value->usul_id.'"> <i class="fa fa-user-plus"></i></a>';
			$html .='&nbsp;<a href="#" class="btn btn-primary btn-flat btn-xs" data-toggle="modal" data-target="#anakModal" data-tooltip="tooltip"  title="Tambah data Anak" data-usul="'.$value->usul_id.'"><i class="fa fa-child"></i></a>';
			$html .='&nbsp;<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Usul BKN" data-toggle="modal" data-target="#kirimModal" data-nip="'.$value->nip.'" data-usul="'.$value->usul_id.'" data-layanan="'.$value->layanan_id.'" ><i class="fa fa-mail-forward"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nomor_usul.'</td>';
			$html .='<td>'.$value->tgl.'</td>';
			$html .='<td>'.$value->nip.'</td>';
			$html .='<td>'.$value->nama_pns.'</td>';
			$html .='<td>'.$value->layanan_nama.'</td>';
			$html .='<td>';
			if(!empty($value->file_pengantar))
			{
				$file = $value->file_pengantar;				
				$html .= '<span data-toggle="tooltip" data-original-title="Ada File Pengantar">
				<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?t='.$this->myencrypt->encode("application/pdf").'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
			}
			else
			{
				$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Pengantar">
				<i class="fa fa-file-o" style="color:red;"></i></span>';
			}
			$html .='</td>';
			$html .='<td>'.$value->jumlah_istri.'</td>';
			$html .='<td>'.$value->jumlah_anak.'</td>';
			$html .='<td>'.$value->created_date.'</td>';
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	public function getKelengkapan()	{
		
		$nip         = $this->myencrypt->decode($this->input->get('n'));
		$berkas      = $this->usul->getUploadDokumen($nip);
		$layanan     = $this->myencrypt->decode($this->input->get('l'));
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr>
							<td colspan="4">LAYANAN USUL '.$layanan.'</td>
							</tr>
							<tr>
								<th>ADA</th>
								<th>NAMA BERKAS</th><th></th></tr></thead>';
		foreach($berkas->result() as $value)
		{
			$html .='<tr>
						<td><i class="fa fa-check" style="color:green;"></i></td>	
						<td>'.$value->keterangan.'</td>
						<td><button class="btn bg-navy btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat File" data-toggle="modal" data-target="#showFile" data-id="?f='.$this->myencrypt->encode($value->file_name).'&t='.$this->myencrypt->encode($value->file_type).'"><i class="fa fa-search"></i></button></td>
						</tr>';	
		}
		$html .='</table>';
		
		echo $html;
	}
	
	
	
	public function getInlineTaspen()
	{
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$type      = $this->myencrypt->decode($this->input->get('t'));
					
		ob_clean();			
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:'.$type.'');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		readfile(base_url().'uploads/taspen/'.$file);
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
		readfile(base_url().'taspen/dokumen/'.$file);
	}	
	
	/*  cek berkas */
	public function lacak()
	{
	   
		$data['menu']      		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']      		=  $this->auth->getName();
        $data['jabatan']   		=  $this->auth->getJabatan();
		$data['member']	   		=  $this->auth->getCreated();
		$data['avatar']	   		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/berkas/index',$data);
	}
	
	public function getBerkas()
	{
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'trim|required');		
		
		$perintah           		   = $this->input->post('perintah');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']      =  $this->menu->build_menu();
			$data['lname']     =  $this->auth->getLastName();        
			$data['name']      =  $this->auth->getName();
			$data['jabatan']   =  $this->auth->getJabatan();
			$data['member']	   =  $this->auth->getCreated();
			$data['avatar']	   =  $this->auth->getAvatar();			
			
			$data['show']  	   = FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/berkas/index',$data);
		}
		else
		{	
			$q	  						   = $this->berkas->getBerkas();
				
			if($perintah == 1)			{
				
				$data['menu']     		=  $this->menu->build_menu();
				$data['lname']    		=  $this->auth->getLastName();        
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['usul']	  		=  $q;				
				$data['show']  			=  TRUE;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/berkas/index',$data);
			}
			else
			{	
				$this->_getBerkasExcel($q);
			}
		}	
	}
	
	public function getBerkasAll()
	{
		$berkas	  						   = $this->berkas->getBerkas();	
		
		$html = '';
		$html .='<table id="tb-lacak" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th></th>
								<th>NOMOR</th>									
								<th>NIP</th>
								<th>NAMA PNS</th>
								<th>NAMA</th>
								<th>UPDATE</th>
								<th>PELAYANAN</th>
								<th>FILE</th>
								<th>STATUS</th>
								<th>TAHAPAN</th>
							</tr>
					</thead>';
		$html .='<tbody>';	
		$link  ='';
		$link2 ='';
		
		foreach($berkas->result() as $value)
		{
			if($value->usul_status == 'BTL')
			{	
				$link= '<a href="#" class="btn bg-maroon btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Ulang Berkas BTL ini" data-toggle="modal" data-target="#kirimModal" data-nip="'.$this->myencrypt->encode($value->nip).'" data-usul="'.$this->myencrypt->encode($value->usul_id).'" ><i class="fa fa-mail-forward"></i></a>';	
				$link2= '&nbsp;<a href="#" class="btn bg-orange btn-xs" data-tooltip="tooltip"  title="Cek Keterangan Alasan BTL" data-toggle="modal" data-target="#cekModal" data-id="?n='.$this->myencrypt->encode($value->nip).'&a='.$this->myencrypt->encode($value->usul_id).'">'.$value->usul_status.'</a>';
			}
			else
			{
				$link2='<span class="'.$value->bg.'">'.$value->usul_status.'</span>';
			}
			
			$html .='<tr>';
			$html .='<td>';
			$html .= $link;
			$html .='</td>';
			$html .='<td>'.$value->	nomor_usul.'</td>';
			$html .='<td>'.(!empty($value->nip_lama) ? $value->nip_lama.' / '.$value->nip_baru : $value->nip).'</td>';
		    $html .='<td>'.$value->nama_pns.'</td>';
			$html .='<td>'.$value->nama_janda_duda.'</td>';
			$html .='<td>'.$value->updated_date.'</td>';
			$html .='<td>'.$value->layanan_nama.'</td>';
			$html .='<td>';
			if(!empty($value->file_persetujuan))
			{
				$file = $value->file_persetujuan;
				
				$html .='<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
				<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#lihatFileModal" data-id="?t='.$this->myencrypt->encode('application/pdf').'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
			}
			else
			{
				$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
				<i class="fa fa-file-o" style="color:red;"></i></span>';
			}			
			$html .='</td>';
			$html .='<td>';
			$html .= $link2;
			$html .='</td>';
			$html .='<td>';
			$html .= '<span class="badge bg-maroon">'.$value->tahapan_nama.'</span>';
			$html .='</td>';
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	private function _getBerkasExcel($q)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "DAFTAR USUL TASPEN".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/x-msdownload');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR USUL TASPEN';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';	
		$html .= '<tr><td  colspan=2>TANGGAL</td><td>'.date('d-M-Y H:i:s').'</td></tr>';				
		$html .= '</table><p></p>';		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NOMOR</th>					
					<th>NIP</th>
					<th>NAMA PNS</th>
					<th>NAMA</th>
					<th>UPDATE</th>
					<th>PELAYANAN</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					<th>TAHAPAN</th>'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {				
				$html .= "<tr><td>{$r->nomor_usul}</td>";               			
				$html .= "<td class=str>".(!empty($r->nip_lama) ? $r->nip_lama.'/'.$r->nip_baru : $r->nip)."</td>";	
                $html .= "<td>{$r->nama_pns}</td>";	
				$html .= "<td>{$r->nama_janda_duda}</td>";	
				$html .= "<td>{$r->updated_date}</td>";
				$html .= "<td>{$r->layanan_nama}</td>";
				$html .= "<td>{$r->usul_status}</td>";
				$html .= "<td>{$r->usul_alasan}</td>";
				$html .= "<td>{$r->tahapan_nama}</td>";
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
	
	public function getTempIstriAll()
	{
		$id			= $this->input->post('usul_id');
		$usul 		= $this->usul->getTempIstri($id);		
		$html = '';
		$html .='<table id="tb-istri" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th>Aksi</th>
								<th>Nama</th>
								<th>Nama Kecil</th>
								<th>Tempat/Tgl Lahir</th>
								<th>Tanggl Nikah</th>
								<th>Tanggal Pendaftaran</th>
								<th>Tanggal Cerai</th>
								<th>Tanggal Wafat</th>	
								<th style="width:100px;">Alamat</th>
							</tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
			$html .='<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Istri" data-toggle="modal" data-target="#istriModal" data-id="'.$value->mutasi_id.'" data-nama="'.$value->nama.'" data-nama_kecil="'.$value->nama_kecil.'" data-tempat_lahir="'.$value->tempat_lahir.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-tgl_nikah="'.$value->tgl_nikah.'" data-tgl_pendaftaran="'.$value->tgl_pendaftaran.'" data-tgl_cerai="'.$value->tgl_cerai.'" data-tgl_wafat="'.$value->tgl_wafat.'" data-alamat="'.$value->alamat.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Istri" data-toggle="modal" data-target="#hapusModal" data-id="'.$value->mutasi_id.'"><i class="fa fa-remove"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nama.'</td>';
			$html .='<td>'.$value->nama_kecil.'</td>';
		    $html .='<td>'.$value->tempat_lahir.'/'.$value->tgl_lahir.'</td>';
			$html .='<td>'.$value->tgl_nikah.'</td>';
			$html .='<td>'.$value->tgl_pendaftaran.'</td>';
			$html .='<td>'.$value->tgl_cerai.'</td>';
			$html .='<td>'.$value->tgl_wafat.'</td>';
			$html .='<td>'.$value->alamat.'</td>';
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	public function hapusTempIstri()
	{
		$data['result']		= $this->usul->hapusTempIstri();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	
	public function simpanTempAnak()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('tgl_cerai', 'Tanggal Cerai', 'trim');
		$this->form_validation->set_rules('tgl_wafat', 'Tanggal Wafat', 'trim');
		$this->form_validation->set_rules('nama_ibu_ayah', 'nama_ibu_ayah', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Silahkan Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$temp_id			 = $this->input->post('temp_mutasi_id');
			
			if(!empty($temp_id))
			{	
				$result				 = $this->usul->updateTempAnak();
			}
			else
			{
				$result				 = $this->usul->simpanTempAnak();
			}
			
			$response   		 =  $result['response'];
			$data['pesan']       =  $result['pesan'];
			
			if($response != TRUE)
			{
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				return FALSE;	
			}
			else
			{
				$data['pesan']	= $result['pesan'];
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}
		
		}
	}	
	
	public function getTempAnakAll()
	{
		$id			= $this->input->post('usul_id');		
		$usul 		= $this->usul->getTempAnak($id);		
		$html = '';
		$html .='<table id="tb-anak" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th rowspan="2">Aksi</th>
								<th rowspan="2">Nama</th>
								<th rowspan="2">LK/PR</th>
								<th rowspan="2">Tgl Lahir</th>
								<th colspan="3">Keterangan Tentang Ibu/Ayah </th>								
							</tr>
							<tr>									
								<th>Nama</th>
								<th>Cerai Tgl</th>
								<th>Meninggal Tgl</th>
							</tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
			$html .='<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Anak" data-toggle="modal" data-target="#anakModal" data-id="'.$value->mutasi_id.'" data-nama="'.$value->nama.'" data-sex="'.$value->sex.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-tgl_cerai="'.$value->cerai_tgl.'" data-tgl_wafat="'.$value->meninggal_tgl.'" data-nama_ibu_ayah="'.$value->nama_ibu_ayah.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Anak" data-toggle="modal" data-target="#hapusAnakModal" data-id="'.$value->mutasi_id.'"><i class="fa fa-remove"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nama.'</td>';
			$html .='<td>'.$value->sex.'</td>';
			$html .='<td>'.$value->tgl_lahir.'</td>';
			$html .='<td>'.$value->nama_ibu_ayah.'</td>';
			$html .='<td>'.$value->cerai_tgl.'</td>';
			$html .='<td>'.$value->meninggal_tgl.'</td>';			
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	public function hapusTempAnak()
	{
		$data['result']		= $this->usul->hapusTempAnak();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function simpanTempAnakJd()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');		
		$this->form_validation->set_rules('nama_ibu', 'nama_ibu', 'required');
		$this->form_validation->set_rules('nama_ayah', 'nama_ayah', 'required');
		$this->form_validation->set_rules('keterangan', 'keterangan', 'required');
		
	    $this->form_validation->set_message('required', '{field} tidak boleh kosong');

		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Silahkan Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$temp_id			 = $this->input->post('jd_dd_anak_id');
			
			if(!empty($temp_id))
			{	
				$result				 = $this->usul->updateTempAnakJd();
			}
			else
			{
				$result				 = $this->usul->simpanTempAnakJd();
			}
			
			$response   		 =  $result['response'];
			$data['pesan']       =  $result['pesan'];
			
			if($response != TRUE)
			{
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
				return FALSE;	
			}
			else
			{
				$data['pesan']	= $result['pesan'];
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}
		
		}
	}	
	
	public function hapusTempAnakJd()
	{
		$data['result']		= $this->usul->hapusTempAnakJd();
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function getTempAnakJdAll()
	{
		$id			= $this->input->post('usul_id');		
		$usul 		= $this->usul->getTempAnakJd($id);

     		
		$html = '';
		$html .='<table id="tb-anak" class="table table-striped table-condensed">
						<thead>
						    <th>Aksi</th>
							<th>Nama</th>
							<th>Tgl Lahir</th>
							<th>Nama Ayah/Ibu</th>	
							<th>Keterangan</th>
					    </thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
			$html .='<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Anak" data-toggle="modal" data-target="#anakModal" data-id="'.$value->jd_dd_anak_id.'" data-nama="'.$value->nama.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-ibu="'.$value->nama_ibu.'" data-ayah="'.$value->nama_ayah.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
			$html .='&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Anak" data-toggle="modal" data-target="#hapusAnakModal" data-id="'.$value->jd_dd_anak_id.'"><i class="fa fa-remove"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nama.'</td>';			
			$html .='<td>'.$value->tgl_lahir.'</td>';
			$html .='<td>'.$value->nama_ayah.'/'.$value->nama_ibu.'</td>';
			$html .='<td>'.$value->keterangan.'</td>';				
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	
	public function getAlasan(){
	
	    $data['nip']          = $this->myencrypt->decode($this->input->get('n'));
		$data['usul_id']      = $this->myencrypt->decode($this->input->get('a'));
		$alasan               = $this->berkas->getAlasan($data)->row();
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr><td colspan="4"><b>KETERANGAN ALASAN BERKAS BTL</b></td></tr>
						</thead>';
		$html .='<tr>
					<td>'.$alasan->usul_alasan.'</td>
				</tr>';							
		$html .='</table>';
		
		echo $html;
	}	
	
	public function kirimBTL()
	{
		$data['usul_nip']     = $this->myencrypt->decode($this->input->post('usul_nip'));
		$data['usul_id']      = $this->myencrypt->decode($this->input->post('usul_id'));
		
		
		$this->db->trans_begin();
		$data['response']	= $this->berkas->KirimBTL($data);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			
			$data['pesan']		= 'Berkas Gagal dikirim kembali ke BKN';
			
			$this->output
			->set_status_header(406)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		}
		else
		{
			$this->db->trans_commit();
			
			
			// send notifikasi to  telegram			
			$data['usul_id']		= $this->myencrypt->decode($this->input->post('usul_id'));
			$data['nip']			= $this->myencrypt->decode($this->input->post('usul_nip'));
		    $data['pesantg']		= ' berkas BTL dari TASPEN yang sudah dikirim ulang ';
		    $data['tahapan_id']     = '4';
			
			$this->send_to_Telegram($data);			
			
			$data['pesan']		= 'Berkas berhasil dikirim kembali ke BKN';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
			
		}	
		
		
		
		
		
	}
	
	/* Kirim Notifikasi Telegram ke BKN per bidang Pensiun*/
	
	function send_to_Telegram($data)
	{
		$usul_id		= $data['usul_id'];
		$nip			= $data['nip'];
		$pesan          = $data['pesantg'];
		$tahapan        = $data['tahapan_id'];
		
		
		$row_usul	    =  $this->usul->getUsul_byid($usul_id,$nip)->row();
		$TelegramAkun   =  $this->usul->getTelegramAkun_bybidang($tahapan);
				
		if($TelegramAkun->num_rows() > 0)
		{	
			foreach($TelegramAkun->result() as $value)
			{	
				// send to telegram API
				if(!empty($value->telegram_id))
				{	
					$this->telegram->sendApiAction($value->telegram_id);
					$text  = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong>  Ada ".$pesan." nih :";
					$text .= "\n Tanggal :".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul :".trim($row_usul->nomor_usul);
					$text .= "\n Nama PNS :".$row_usul->nama_pns;
					($row_usul->layanan_id == 16 || $row_usul->layanan_id == 17 ? $text .= "\n Nama JD/YT :".$row_usul->nama_janda_duda : '' );
					$text .= "\n Layanan :".$row_usul->layanan_nama.'</pre>';					
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');	
					//var_dump($text);
				}	
			}
		}
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */