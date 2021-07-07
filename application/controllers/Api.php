<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Api extends REST_Controller {
	
	
    public function __construct() {
        parent::__construct();
		
	    $this->load->library(array('form_validation','Token'));
        $this->load->model('api/api_model');

    }
	
	/**
     * @AclName Api
     */
	 
    public function login_post() {
		
        $username 		 = $this->post('username');
        $password		 = SHA1($this->post('password'));
		
		$id = $this->api_model->get_user($username);
		
		
		if($id->num_rows() > 0) 
		{
			$row			   		 = $id->row();
			$hashpassword            = $row->password;
			
			if($password   === $hashpassword)
			{					
				$token['user_id'] 	   	 = $row->user_id;
				$token['active']   		 = $row->active;
				$token['username'] 		 = $username;
				$token['instansi']   	 = $row->id_instansi;
				$date 			   		 = new DateTime();
				$token['iat'] 	   		 = $date->getTimestamp();
				$token['exp']      		 = $date->getTimestamp() + 60*60*5;
				
				
				// return response
				$data                    = array('user_id' 		  => $row->user_id,
				                                 'nip'            => $row->nip,
												 'last_name'	  => $row->last_name,
												 'first_name'     => $row->first_name,
												 'email'    	  => $row->email,
												 'created_date'   => $row->created_date,
												 'last_access'    => $row->last_access,
				);
				
				$meta                   = array('token'        => $this->token->generateToken($token));
				
				$output['message']       = "success";
				$output['data']      	 = $data;
				$output['meta']      	 = $meta;
				$output['response']      = TRUE; 
				$this->set_response($output, REST_Controller::HTTP_OK);
			}
			else
			{
				
				$output['message']       = "The given data was invalid";
				$output['response']      = FALSE; 
				$this->response($output, REST_Controller::HTTP_BAD_REQUEST);

			}		
		}
		else 
		{
			
			$output['message']       = "The given data was invalid";
			$output['response']      = FALSE; 
			$this->response($output, REST_Controller::HTTP_BAD_REQUEST);
		}  
 
		
		
    }
	
	/* upload  dokumen */
	
	public function uploadDokumen_post()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;	
				
				
				
				$target_dir						='./uploads/'.$instansi;		
				$config['upload_path']          = $target_dir;
				$config['allowed_types']        = 'pdf';
				$config['max_size']             = 5120;
				$config['encrypt_name']			= FALSE;	
				$config['overwrite']			= TRUE;	
				$config['detect_mime']			= TRUE;
		
				if(!file_exists($target_dir)){
					mkdir($target_dir,0777);
				}

		
				// validasi NIP
			    if(!$this->api_model->isAdaNIP($_FILES['file']['name']))
				{	
					$error = array( 'response'  => FALSE, 
									'message'   => 'Dokumen yang anda upload ini tidak terdapat NIP');
					$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
					
					return FALSE;
					
				}
				
				
				if(!$this->api_model->_is_arsip($_FILES['file']['name']))
				{
					
					$error = array( 'response'   => FALSE, 
									'message'    => 'File ini tidak diperbolehkan untuk diupload');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);

					return FALSE;
                }

		
				if(!$this->api_model->isSesuaiFormat($_FILES['file']['name']))
				{
					$error = array( 'response'   => FALSE,
									'message' 	 => 'File belum sesuai format, silahkan cek pada daftar tabel');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
                    return FALSE;
				}
				
				

				if(!$this->api_model->isMinorValid($_FILES['file']['name']))
				{
					$error = array(  'response'   => FALSE,
									 'message' 	  => 'File KODE atau TAHUN salah');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE); 
					return FALSE;
				}	

                				
		
				// Try cek file		
				$cekFile	= $this->api_model->isAllowSize($_FILES['file']);
				$response   = $cekFile['response'];
				if(! $response)
				{
					$error = array( 'response'     => FALSE,
					                'message'      => $cekFile['pesan']);
					$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
					return FALSE;
				}
		
		        
				// load upload lib
				$this->load->library('upload');
				$this->upload->initialize($config);
				
				



				if ( ! $this->upload->do_upload('file'))
				{
						$error = array( 'response'     => FALSE,
						                'message'      => strip_tags($this->upload->display_errors()));
						$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
                        
						
				}
				else
				{
						$data 					= $this->upload->data();
						
					
						$data['upload_by'] 		= $userId;	
				        $data['id_instansi']	= $instansi;
						$data['api']            = 1;
				
						
						$result		= $this->api_model->insertUpload($data);
						
						
					
						if($result['response'])
						{
							$out['response']    = TRUE;
							$out['insert']      = TRUE;
							$out['message'] 	= $result['pesan'];
							$this->set_response($out, REST_Controller::HTTP_OK);
							
								
						}
						else
						{
							$out['response']    = TRUE;
							$out['update']      = $this->api_model->updateFile($result);
							$out['message'] 	= 'File dokumen kepegawaian sudah ada, overwrite file';
							$this->set_response($out, REST_Controller::HTTP_OK);
							
						}			
						
				}
				
				
			    
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}         		
				
				
		
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
    }
	
	/* get dokumen upload*/
	public function listUploadDokumen_get()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$data['instansi']       = $instansi;
					$data['searchby']       = 1;
					$data['search']         = $this->get('nip');
			
					$q				  = $this->api_model->getDaftar($data);	
					$out['response']  = TRUE;
					$out['message']   = ($q->num_rows() > 0 ? 'List Of Document Files' :  'No Files' );
					$out['size']      =  $q->num_rows();
					$out['files']     =  $q->result_array();
					$this->set_response($out, REST_Controller::HTTP_OK);
					
				}	
                				
			
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}    
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 
	
	/* HAPUS file dokumen */
    public function hapusDokumen_delete()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->delete());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path ='/var/www/html/uploads/'.$instansi.'/';
					$file = $this->delete('name');
					$data['instansi']         = $instansi;
					$data['file']             = $file;
					
					
				    if(@unlink($path.$file) && $this->api_model->hapusFile($data))
					{
						$out['response']  = TRUE;
						$out['pesan'] 	 = 'File dokumen berhasil dihapus';
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']  = FALSE;
						$out['pesan'] 	 = 'File dokumen Gagal dihapus';
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);	
					}

                }	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 		


    /* GET CONTENT DOKUMEN*/
	public function dokumen_get()
	{
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path      ='/var/www/html/uploads/'.$instansi.'/';
					$file      = $this->get('name');
					$flok      = $path.$instansi.'/'.$file;
					
					if(file_exists($flok))
					{
					    $out['response']     		 = TRUE;
						$out['file_name']            = $file;
						$out['file_content'] 	     = base64_encode(file_get_contents($flok));
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']     		 = FALSE;
						$out['message']     		 = "File dokumen tidak ditemukan";
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);

					}		
					
				}	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
	}	
   
   
    /* upload photo */   
    public function uploadPhoto_post()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;	
				
				
				$target_dir						='./photo/'.$instansi;		
				$config['upload_path']          = $target_dir;
				$config['allowed_types']        = 'jpeg|jpg|JPG|JPEG';
				$config['max_size']             = 1024;
				$config['encrypt_name']			= FALSE;	
				$config['overwrite']			= TRUE;	
				$config['detect_mime']			= TRUE;
				
				if (!is_dir($target_dir)) {
					mkdir($target_dir, 0777, TRUE);
				}
				
				if(!$this->api_model->isAllowFormatPhoto($_FILES['file']['name']))
				{	
					$error = array(  'response'   => FALSE,
									 'message' 	  => 'File Format photo belum sesuai');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE); 
					return FALSE;
					
				}		
				
				if(! $this->api_model->_is_photo($_FILES['file']['name'])){
					
					$error = array(  'response'   => FALSE,
									 'message' 	  => 'NIP photo SALAH');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE); 
					return FALSE;
					
				}	
				
				$haystack = $_FILES['file']['name'];
		

				if( stripos( $haystack, "KARIS" ) !== false) {
					$nip                            = $this->api_model->_extract_numbers($haystack);
					$config['file_name']            = 'KARIS_'.$nip[0];
				}		
				
				if( stripos( $haystack, "KARSU" ) !== false) {
					$nip                            = $this->api_model->_extract_numbers($haystack);
					$config['file_name']            = 'KARSU_'.$nip[0];
				}	
				
				if( stripos( $haystack, "KARPEG" ) !== false) {
					$nip                            = $this->api_model->_extract_numbers($haystack);
					$config['file_name']            = 'KARPEG_'.$nip[0];
				}									
						
				
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('file'))
				{
					$error = array( 'response'     => FALSE,
						            'message'      => strip_tags($this->upload->display_errors()));
					$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
					
				}
				else
				{

					$data 		= $this->upload->data();
					
					$data['upload_by'] 		= $userId;	
				    $data['id_instansi']	= $instansi;
					$data['api']            = 1;
						
					$result		= $this->api_model->insertUploadPhoto($data);
					$this->resizeImage($instansi,$data);
					
					if($result['response'])
					{
						$out['response']    = TRUE;
						$out['insert']      = TRUE;
						$out['message'] 	= $result['pesan'];
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']    = TRUE;
						$out['update']      = $this->api_model->updatePhoto($result);
						$out['message'] 	= 'Photo sudah ada, overwrite photo';
						$this->set_response($out, REST_Controller::HTTP_OK);
							
					}			
				}		
		
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 	

    /* List Upload Photo */
	public function listUploadPhoto_get()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$data['instansi']       = $instansi;
					$data['searchby']       = 1;
					$data['search']         = $this->get('nip');
					$q				        = $this->api_model->getDaftarPhoto($data);
					
					$out['response']  = TRUE;
					$out['message']   = ($q->num_rows() > 0 ? 'List Of Photo Files' :  'No Files' );
					$out['size']      =  $q->num_rows();
					$out['files']     =  $q->result_array();
					$this->set_response($out, REST_Controller::HTTP_OK);
				}	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 	
	
	/* HAPUS Photo */
	public function hapusPhoto_delete()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->delete());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path ='/var/www/html/photo/'.$instansi.'/';
					$file = $this->delete('name');
					$data['instansi']         = $instansi;
					$data['file']             = $file;
					
					
				    if(@unlink($path.$file) && $this->api_model->hapusPhoto($data))
					{
						$out['response']  = TRUE;
						$out['pesan'] 	 = 'Photo berhasil dihapus';
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']  = FALSE;
						$out['pesan'] 	 = 'Photo Gagal dihapus';
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);	
					}

                }	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}		
  
    public function resizeImage($instansi,$data)
    {	  
	    $source_path = $data['full_path'];
        $target_path = $data['full_path'];	  
	  
        //Compress Image
		$config['image_library']		= 'gd2';
		$config['source_image']			= $source_path;
		$config['create_thumb']			= FALSE;
		$config['maintain_ratio']		= FALSE;
		$config['width']				= 128;
		$config['height']				= 150;
		$config['new_image']			=  $target_path;
		$config['quality']				= '100%';
		$this->load->library('image_lib', $config);
		
        if (!$this->image_lib->resize()) {
            
			$error = array( 'response'     => FALSE,
							'message'      => strip_tags($this->image_lib->display_errors()));
			$this->set_response($error, REST_Controller::HTTP_BAD_REQUEST);
			
			
        }
        $this->image_lib->clear();
    }	
	
	
	/* TASPEN */
	public function taspenUpload_post()
	{

		$headers = $this->input->request_headers();
		
        if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				
				$rules = array(					
					array(
						'field' => 'jenis',
						'label' => 'jenis',
						'rules' => 'required'
					),
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->post());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $out    = [ 'response'   =>  FALSE, 'message' => 'Bad Request '];
					$this->response($out, REST_Controller::HTTP_BAD_REQUEST);
				
				}
                else	
                {					
					
					$jenis						= $this->post('jenis');
					$nip						= $this->post('nip');
					
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
						$error = array( 'response'     => FALSE,
						                'message'      => strip_tags($this->upload->display_errors()));
					    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);			
					}
					else
					{
						
						$data 			= $this->upload->data();
						$is_image       = $data['is_image'];
						
						if($is_image === TRUE)
						{
							$this->resizeImageTaspen($data);
						}
						
						
						$data['upload_by'] 		= $userId;	
				        $data['id_dokumen']		= $jenis;
						$data['nip']            = $nip;
					    $data['api']            = 1;
					
                        $result         = $this->api_model->insertUploadTaspen($data);
						
						$response       = $result['response'];
						
						if($response === TRUE)
						{					
							$out['response']    = TRUE;
						    $out['insert']      = $response;
						    $out['message'] 	= $result['pesan'];
							$out['file_name'] 	= $data['file_name'];
							$out['file_type'] 	= $data['file_type'];
							$out['file_size'] 	= $data['file_size'];
							$out['file_ext'] 	= $data['file_ext'];
							$out['is_image'] 	= $data['is_image'];
                            $this->set_response($out, REST_Controller::HTTP_OK);							
						}
						else
						{
						  	$out['response']    = TRUE;
						    $out['update']      = $this->api_model->updateFileTaspen($data);
							$out['message']	    = 'File telah ada, overwrite file';
							$out['file_name'] 	= $data['file_name'];
							$out['file_type'] 	= $data['file_type'];
							$out['file_size'] 	= $data['file_size'];
							$out['file_ext'] 	= $data['file_ext'];
							$out['is_image'] 	= $data['is_image'];
							
							$this->set_response($out, REST_Controller::HTTP_OK);

						}                							
					}
					
				
				}
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  		
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}				
		
		
	}	
	
	
	/* GET TASPEN UPLOAD*/
	public function taspenUpload_get()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				
				$rules = array(					
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$data['searchby']       = 1;
					$data['search']         = $this->get('nip');
					$q				        = $this->api_model->getDaftarTaspen($data);
					
					$out['response']  = TRUE;
					$out['message']   = ($q->num_rows() > 0 ? 'List Of Files' :  'No Files' );
					$out['size']      =  $q->num_rows();
					$out['files']     =  $q->result_array();
					$this->set_response($out, REST_Controller::HTTP_OK);
				}	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 	
	
	/* HAPUS DOKUMEN TASPEN*/
	 public function taspenUpload_delete()
	{

		$headers = $this->input->request_headers();
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->delete());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path ='/var/www/html/uploads/taspen/';
					$file = $this->delete('name');
					$data['file']             = $file;
					
					
				    if(@unlink($path.$file) && $this->api_model->hapusFileTaspen($data))
					{
						$out['response']  = TRUE;
						$out['pesan'] 	 = 'File berhasil dihapus';
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']  = FALSE;
						$out['pesan'] 	 = 'File Gagal dihapus';
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);	
					}

                }	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 		

	
	function resizeImageTaspen($data)
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
            $error = array('response'     => FALSE,'error' => $this->image_lib->display_errors());
			$this->set_response($error, REST_Controller::HTTP_BAD_REQUEST);
        }
        $this->image_lib->clear();
    }
	
	/* GET CONTENT DOKUMEN*/
	public function taspenDokumen_get()
	{
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path      ='d:/xampp/htdocs/paperless/uploads/taspen/';
					$file      = $this->get('name');
					$flok      = $path.$file;
					
					if(file_exists($flok))
					{
                        $this->load->helper('file');

						$path                        = pathinfo($flok);
						$out['response']     		 = TRUE;
						$out['file_name']            = $file;
						$out['file_ext']             = $path['extension'];
						$out['file_mime']            = get_mime_by_extension($file);
						$out['file_content'] 	     = base64_encode(file_get_contents($flok));
						$this->set_response($out, REST_Controller::HTTP_OK);
							
						
							
					}
					else
					{
						$out['response']     		 = FALSE;
						$out['message']     		 = "File dokumen tidak ditemukan";
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);

					}		
					
				}	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
	}	
	
	/* VALIDASI SK*/
	public function validasiSK_get()
	{
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				
				$rules = array(					
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}
				else
				{
					$data['instansi']       = '';
					$data['searchby']       = 1;
					$data['search']         = $this->get('nip');
					$q				        = $this->api_model->getValidasiSK($data);
					
					$out['response']  = TRUE;
					$out['message']   = ($q->num_rows() > 0 ? 'List Of Files' :  'No Files' );
					$out['size']      = $q->num_rows();
					$files            = $q->result_array();
					
										
					$out['files']     = $files;
					$this->set_response($out, REST_Controller::HTTP_OK);
					

				}	

				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	
	
	
	/* GET CONTENT DOKUMEN PERTEK DAN SK PENSIUN*/
	public function validasiSKDokumen_get()
	{
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					),
					array(
						'field' => 'instansi',
						'label' => 'instansi',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$file          = $this->get('name');
					$instansi      = $this->get('instansi');
					
					$path      ='d:/xampp/htdocs/paperless/uploads/'.$instansi.'/';
					$flok      = $path.$file;
					
					
					if(file_exists($flok))
					{
                        $this->load->helper('file');

						$path                        = pathinfo($flok);
						$out['response']     		 = TRUE;
						$out['file_name']            = $file;
						$out['file_ext']             = $path['extension'];
						$out['file_mime']            = get_mime_by_extension($file);
						$out['file_content'] 	     = base64_encode(file_get_contents($flok));
						$this->set_response($out, REST_Controller::HTTP_OK);
							
						
							
					}
					else
					{
						$out['response']     		 = FALSE;
						$out['message']     		 = "File dokumen tidak ditemukan";
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);

					}		
					
				}	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
	}	
				
}