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
				$token['id'] 	   		 = $row->id;
				$token['pnsId']    		 = $row->pns_id;
				$token['active']   		 = $row->active;
				$token['username'] 		 = $username;
				$date 			   		 = new DateTime();
				$token['iat'] 	   		 = $date->getTimestamp();
				$token['exp']      		 = $date->getTimestamp() + 60*60*5;
				
				
				// return response
				$data                    = array('id' 			  => $row->id,
				                                 'pns_id'         => $row->pns_id,
												 'name'			  => $row->nama,
												 'first_name'     => $row->first_name,
												 'email'    	  => $row->email,
												 'photo'    	  => $row->photo,
												 'created_date'   => $row->created_date,
												 'last_access'    => $row->last_access,
				);
				
				$meta                   = array('token'        => $this->token->generateToken($token), 
												'location'     => $row->latlong,
												'address'      => $row->address
				);
				
				$output['message']       = "success";
				$output['data']      	 = $data;
				$output['meta']      	 = $meta;
				$output['status']        = TRUE; 
				$output['device']        = $this->post('deviceid');; 
				$this->set_response($output, REST_Controller::HTTP_OK);
			}
			else
			{
				
				$output['message']       = "The given data was invalid";
				$output['status']        = FALSE; 
				$error                   = array('message' => 'The provided credentials are incorect '
				);
				$output['errors']        = $error;
				
				
				$this->response($output, REST_Controller::HTTP_BAD_REQUEST);

			}		
		}
		else {
			
			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 
			$error                   = array('message' => 'The provided credentials are incorect '
			);
			$output['errors']        = $error;
			
			
			$this->response($output, REST_Controller::HTTP_BAD_REQUEST);
		}  
 
		
		
    }
	
		 
    public function forgotPassword_post() {
		
        $email 		 	 = $this->post('email');	
		$id 			 = $this->api_model->get_user_byEmail($email);
	
		
		if($id->num_rows() > 0) 
		{
			$output['message']       = "Password reset link has been send to your email";
			$output['status']        = TRUE; 			
			
			$this->set_response($output, REST_Controller::HTTP_OK);
			
		}
		else 
		{
			
			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 			
			$this->response($output, REST_Controller::HTTP_BAD_REQUEST);
		}  

		
		
    }
	
	
	public function timeCek_post() {
		
        
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{			
			//ambil jam dan menit
			$jam = date('H:i');

			//atur  jam absen
			if ($jam > '00:00' && $jam < '07:01') {
				$presType = 0;
				$text     = "Belum Boleh Absen";
			}else if ($jam > '07:00' && $jam < '12:01') {
				$presType = 1;
				$text     = "Check-In Pagi";
			} elseif ($jam >= '12:01' && $jam < '13:01') {
				$presType = 2;
				$text     = "Check-In Siang";
			} elseif ($jam < '18:00') {
				$presType = 3;
				$text     = "Check-In Sore";
			} else {
				$presType = 4;
				$text     = "Check-In Lembur";
			}
			
			// return response
			$data                    = array('jam' 			      => $jam,
											 'tipe'			      => $presType,		
                                             'title'			  => $text,												 
			);
			
			$output['message']       = "success";
			$output['status']        = TRUE; 
			$output['data']      	 = $data;
			$this->set_response($output, REST_Controller::HTTP_OK);		  
		}
		else
		{	

			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
    }
	
	
	public function workCek_post() {
		
		
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->id;	
				
			    // cek lokasi
				$id 			= $this->api_model->get_lokasiAbsen($userId);
				
				if($id->num_rows() > 0) 
				{
					$row                     = $id->row();
					$data                    = array('latlong'  => $row->latlong,
													 'alamat'   =>  $row->address
											);
					$output['message']       = "Success";
					$output['status']        = TRUE; 
					$output['lokasi']        = $data;
					$output['lock_address']  = $row->lock_address; 
					$output['face_detect']   = $row->face_detect; 
					
					$this->set_response($output, REST_Controller::HTTP_OK);					
				}
				else
				{
					$output['message']       = "The given data was invalid";
					$output['status']        = FALSE; 
					$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
				}			
				
			} catch (Exception $e) {
					$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}         		
				
				
		
		}
		else
		{	

			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
    }
	
	public function changePassword_post() {
		
		
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			$rules = array(				
				array(
					'field' => 'passwordOld',
					'label' => 'passwordOld',
					'rules' => 'required'
				),
				array(
					'field' => 'password',
					'label' => 'password',
					'rules' => 'required'
				),
				array(
					'field' => 'passwordConfirmation',
					'label' => 'passwordConfirmation',
					'rules' => 'required|matches[password]'
				)
			);
			
			// validation input
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules($rules);
				
			if($this->form_validation->run() == FALSE)
			{
			   $output['status']		= FALSE;
			   $output['message']		= 'The given data was invalid';
			  
			   $this->response($output, REST_Controller::HTTP_BAD_REQUEST);			
			}		
			else
			{
				try {		
					// try decode
					$decoded 		= $this->token->validateToken($headers['Token']);				
					$userId 		= $decoded->id;	
					
					// get user 
					$id 			= $this->api_model->get_user_byId($userId);
					
					if($id->num_rows() > 0) 
					{
						$row                     = $id->row();
						$hash_password           = $row->password;
						
						if($hash_password  ===  SHA1($this->post('passwordOld')))
						{
            				$password                = $this->post('password');
							
							$db_debug 			= $this->db->db_debug; 
					        $this->db->db_debug = FALSE; 
							
							if (!$this->api_model->changePassword($userId,$password))
							{
								$error = $this->db->error();			
								if(!empty($error))
								{
									$output['status']		= FALSE;
									$output['message']		= $error['message'];
									$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);	
								}						
							}
							else
							{
								$output['status']		= TRUE;
								$output['message']		= 'Success'; 
								$this->set_response($output, REST_Controller::HTTP_OK);		
							}	
							
							$this->db->db_debug = $db_debug; //restore setting		
						
                        }
						else
					    {
                            $output['message']       = "Required Valid Old password";
						    $output['status']        = FALSE; 	
						    $this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
                        }							
					}
					else
					{
						$output['message']       = "The given data was invalid";
						$output['status']        = FALSE; 
						$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
					}			
					
				} catch (Exception $e) {
						$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
						$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
				} 			
				
			}	
		}
		else
		{	

			$output['message']       = "The given data was invalid need TOKEN";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
    }
	
	
	
	public function logout_post() {
		
		
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				$output['message']       = "logout success";
				$output['status']        = TRUE; 
								
				$this->set_response($output, REST_Controller::HTTP_OK);					
				
			} catch (Exception $e) {
					$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			} 
		}
		else
		{	

			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
    }
	
	public function getPhoto_get()
	{
		$headers = $this->input->request_headers();
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			try {
				
				$decoded 		= $this->token->validateToken($headers['Token']);				
				$old            = $this->api_model->getPhoto($decoded->pnsId);
				
				if($old->num_rows() > 0)
				{
					$old_photo = $old->row()->photo;
					
					// hapus photo lama
					if(!empty($old_photo))
					{
						$output['status']		= TRUE;
						$output['photo']		= $old_photo; 
						$output['base_url']     = base_url() . "photo/";
						
						$this->set_response($output, REST_Controller::HTTP_OK);	
					}			
					else
					{
						$output['status']		= TRUE;
						$output['photo']		= "avatar-01.jpg"; 
						$output['base_url']     = base_url(). "photo/";
						$this->set_response($output, REST_Controller::HTTP_OK);		
					}	
				}	
				
			} catch (Exception $e) {
					$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
					
			}   		
		}
		else
		{	

			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	public function updatePhoto_post() {
		
		
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			try {
			    
				$decoded 		= $this->token->validateToken($headers['Token']);
				
				$target_dir						='./photo/';		
				$config['upload_path']          = $target_dir;
				$config['allowed_types']        = 'jpg|png';
				$config['encrypt_name']			= FALSE;	
				$config['overwrite']			= TRUE;	
				//$config['file_name']            = $decoded->pnsId;
				
				$this->load->library('upload', $config);
				
				// coba upload file		
				if ( ! $this->upload->do_upload('file'))
				{		
					$output['status']		= FALSE;
					$output['message']		= strip_tags($this->upload->display_errors());
					$this->set_response($output, REST_Controller::HTTP_OK);	
				}
				else
				{					
					
					$db_debug 			= $this->db->db_debug; 
					$this->db->db_debug = FALSE; 	
					
					 // old photo
					$old   = $this->api_model->getPhoto($decoded->pnsId);
					
					
					if($old->num_rows() > 0)
					{
						$old_photo = $old->row()->photo;
						$dataFile 			= $this->upload->data();
					    $update['photo']    = $dataFile['file_name'];
						// hapus photo lama
						if(!empty($old_photo) && ($old_photo != $dataFile['file_name']) )
						{
							// set sesuai serverkan dengan server
							unlink($_SERVER['DOCUMENT_ROOT'].'/api/photo/'.$old_photo);
						}
						
						if (!$this->api_model->updatePhoto($update,$decoded->pnsId)) 
						{
							$error = $this->db->error();			
							if(!empty($error))
							{
								$output['status']		= FALSE;
								$output['message']		= $error['message'];
								$this->set_response($output, REST_Controller::HTTP_OK);	
							}						
						}
						else
						{
							$output['status']		= TRUE;
							$output['message']		= "Photo berhasil diupdate"; 
							$this->set_response($output, REST_Controller::HTTP_OK);		
						}	
					}				
					
					$this->db->db_debug = $db_debug; //restore setting	
						
				}			
				
			} catch (Exception $e) {
					$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
					
			}   		
		}
		else
		{	

			$output['message']       = "The given data was invalid";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
    }
	
	
	public function attend_post()
	{
		$headers = $this->input->request_headers();
		
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{			
			try {
			    
				$decoded 		= $this->token->validateToken($headers['Token']);
				
				$rules = array(
					array(
						'field' => 'deviceId',
						'label' => 'deviceId',
						'rules' => 'required'
					),
					array(
						'field' => 'workType',
						'label' => 'workType',
						'rules' => 'required|in_list[WFO,WFH]'
					),
					array(
						'field' => 'address',
						'label' => 'address',
						'rules' => 'required'
					),
					array(
						'field' => 'lat',
						'label' => 'lat',
						'rules' => 'required'
					),
					array(
						'field' => 'long',
						'label' => 'long',
						'rules' => 'required'
					)		
				);
				
				
				$this->form_validation->set_rules($rules);
				
				if($this->form_validation->run() == FALSE)
				{
				   $output['status']		= FALSE;
				   $output['message']		= 'Bad Request ';
				   $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$target_dir						='./presensi/';		
					$config['upload_path']          = $target_dir;
					$config['allowed_types']        = 'jpg|png';
					$config['encrypt_name']			= FALSE;	
					$config['overwrite']			= TRUE;	
					
					$this->load->library('upload', $config);	
					
					// coba upload file		
					if ( ! $this->upload->do_upload('photo'))
					{		
						$output['status']		= FALSE;
						$output['message']		= strip_tags($this->upload->display_errors());
						$this->set_response($output, REST_Controller::HTTP_OK);	
					}
					else
					{						
						$dataFile 			= $this->upload->data();
						$file_name          = $dataFile['file_name'];						
						
						//ambil jam dan menit
						$jam = date('H:i');

						
						if ($jam > '00:00' && $jam < '07:01') {
							$presType = 0;
							$text     = "Belum Boleh Absen";
						}else if ($jam > '07:00' && $jam < '12:01') {
							$presType = 1;
							$text     = "Check-In Pagi";
						} elseif ($jam >= '12:01' && $jam < '13:01') {
							$presType = 2;
							$text     = "Check-In Siang";
						} elseif ($jam < '18:00') {
							$presType = 3;
							$text     = "Check-In Sore";
						} else {
							$presType = 4;
							$text     = "Check-In Lembur";
						}
						
						
						$db_debug 			= $this->db->db_debug; 
						$this->db->db_debug = FALSE; 	
						
						$tambah['device_id']			= $this->input->post('deviceId');
						$tambah['pns_id']				= $decoded->pnsId;
						$tambah['presensi_type']		= $presType;
						$tambah['work_type']			= $this->input->post('workType');
						$tambah['location_name']		= $this->input->post('address');
						$tambah['location_lat']		    = $this->input->post('lat');
						$tambah['location_lang']		= $this->input->post('long');
						$tambah['date_check']		    = date('Y-m-d');
						$tambah['file_capture']			= $file_name;
						
			
						if (!$this->api_model->tambahPresensi($tambah)) 
						{
							$error = $this->db->error();			
							if(!empty($error))
							{
								$output['status']		= FALSE;
								$output['message']		= $error['message'];
								$output['face']         = 'Kami berhasil mengenali wajah anda';
								$this->set_response($output, REST_Controller::HTTP_OK);	
							}						
						}
						else
						{
							$output['status']		= TRUE;
							$output['message']		= 'Presensi Berhasil ditambahkan'; 
							$output['face']         = 'Kami berhasil mengenali wajah anda';
							$this->set_response($output, REST_Controller::HTTP_OK);		
						}	
						
						$this->db->db_debug = $db_debug; //restore setting	
								 
								
							
							
							
					
						
					}   		
												 
						
				
				}
				
			} catch (Exception $e) {
					$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
					
			}             
		}
		else
		{	

			$message = [
				'status'  =>  FALSE,
				'message' => 'No TOKEN',
		    ];
			$this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	
	
	public function history_get()
	{
		$headers = $this->input->request_headers();
		
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{			
			try {
			    				
				$rules = array(					
					array(
						'field' => 'awal',
						'label' => 'awal',
						'rules' => 'required'
					),
					array(
						'field' => 'akhir',
						'label' => 'akhir',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);
				
				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['status'   =>  FALSE, 'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					
					
					$date1				   = date_create($this->get('awal'));
					$date2				   = date_create($this->get('akhir'));
					$diff				   = date_diff($date1,$date2);
					
					if($diff->days > 30)
					{
						$output['status']		= FALSE;
						$output['message']		= 'Maksimal 30 hari!'; 							
						$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);		
					}
					else
					{
						$decoded 		= $this->token->validateToken($headers['Token']);
					
						
					
						$input['pnsId']         = $decoded->pnsId;
						$input['awal']          = $this->get('awal');
						$input['akhir']         = $this->get('akhir');					
						$presensi               = $this->api_model->getPresensi($input);
						
											
						$output['status']		= TRUE;
						$output['message']		= 'List of presence by user'; 					
						$output['data'][]	    =  array( 'user_id' 	 => $decoded->id,
														  'status'       => $decoded->active,
														  'username'     => $decoded->username,
														  'pns_id'       => $decoded->pnsId,
														  'detail'       => $presensi->result_array()
						);						
						$this->set_response($output, REST_Controller::HTTP_OK);	
					}			
					
				}	
				
			} catch (Exception $e) {
					$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
					
			} 
		}
		else
		{	

			$message = [
				'status'  =>  FALSE,
				'message' => 'No TOKEN'
		    ];
			$this->set_response($message, REST_Controller::HTTP_UNAUTHORIZED);
		}		
	}					
	
	
	public function registerDevice_post() {
		
		
		$headers = $this->input->request_headers();
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			$rules = array(				
				array(
					'field' => 'deviceRegister',
					'label' => 'deviceRegister',
					'rules' => 'required'
				)
			);
			
			// validation input
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules($rules);
				
			if($this->form_validation->run() == FALSE)
			{
			   $output['status']		= FALSE;
			   $output['message']		= 'The given data was invalid';
			  
			   $this->response($output, REST_Controller::HTTP_BAD_REQUEST);			
			}		
			else
			{
				try {		
					// try decode
					$decoded 		= $this->token->validateToken($headers['Token']);				
					$userId 		= $decoded->id;	
					
					// get user 
					$id 			= $this->api_model->get_user_byId($userId);
					
					if($id->num_rows() > 0) 
					{
						$device             = $this->post('deviceRegister');
						
						$db_debug 			= $this->db->db_debug; 
						$this->db->db_debug = FALSE; 
						
						if (!$this->api_model->setDeviceRegister($userId,$device))
						{
							$error = $this->db->error();			
							if(!empty($error))
							{
								$output['status']		= FALSE;
								$output['message']		= $error['message'];
								$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);	
							}						
						}
						else
						{
							$output['status']		= TRUE;
							$output['message']		= 'Success Device Register'; 
							$this->set_response($output, REST_Controller::HTTP_OK);		
						}	
						
						$this->db->db_debug = $db_debug; //restore setting		
						
                        						
					}
					else
					{
						$output['message']       = "The given data was invalid";
						$output['status']        = FALSE; 
						$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
					}			
					
				} catch (Exception $e) {
						$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
						$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
				} 			
				
			}	
		}
		else
		{	

			$output['message']       = "The given data was invalid need TOKEN";
			$output['status']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_UNAUTHORIZED);
		}
		
		
    }
	
	public function pns_post()
	{
		try {
			    				
			$rules = array(					
				array(
					'field' => 'nip',
					'label' => 'NIP',
					'rules' => 'required'
				)
			);	
				
				
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run() == FALSE)
			{
			   $output['status']		= FALSE;
			   $output['message']		= 'The given data was invalid';
			  
			   $this->response($output, REST_Controller::HTTP_BAD_REQUEST);			
			}		
			else
			{
				// get pns from mirror 
				$pns 			= $this->api_model->get_pns($this->post('nip'));
				
				if($pns->num_rows() > 0) 
				{
					$output['status']		= TRUE;
					$output['message']		= 'Data PNS By NIP'; 					
					$output['data']	        =  $pns->result_array();						
					$this->set_response($output, REST_Controller::HTTP_OK);	
					
				}
				else
				{
					$output['status']		= FALSE;
					$output['message']		= "PNS NOT FOUND";
					$this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
				}				
				
			}
			
		}catch (Exception $e) {
				$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
				$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
				
		}
		
	}
	
	
	public function register_post()
	{
		try {
							
			$rules = array(					
				array(
					'field' => 'pnsId',
					'label' => 'PNSID',
					'rules' => 'required'
				),
				array(
					'field' => 'address',
					'label' => 'Address',
					'rules' => 'required'
				),
				array(
					'field' => 'latlong',
					'label' => 'Latlong',
					'rules' => 'required'
				)
			);	
			
			
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run() == FALSE)
			{
			   $output['status']		= FALSE;
			   $output['message']		= 'The given data was invalid';
			  
			   $this->response($output, REST_Controller::HTTP_BAD_REQUEST);			
			}		
			else
			{
				
				$db_debug 			= $this->db->db_debug; 
				$this->db->db_debug = FALSE; 	
				
				if (!$this->api_model->tambahUser($this->post())) 
				{
					$error = $this->db->error();			
					if(!empty($error))
					{
						$output['status']		= FALSE;
						$output['message']		= $error['message'];
						$this->set_response($output, REST_Controller::HTTP_OK);	
					}						
				}
				else
				{
					$output['status']		= TRUE;
					$output['message']		= 'Resistrasi Berhasil, Silahkan Login'; 
					$this->set_response($output, REST_Controller::HTTP_OK);		
				}	
				
				$this->db->db_debug = $db_debug; //restore setting	
				
				
				
			}
			
		}catch (Exception $e) {
				$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
				$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
				
		} 
		
	}
	
	public function users_get()
    {
        
		$headers = $this->input->request_headers();
		
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{			 
			try {
			    
				//$decoded 		= $this->token->validateToken($headers['token']);
			   
			    // Users from a data store e.g. database
				$users = [
					['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
					['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
					['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
				];

				$id = $this->get('id');

				// If the id parameter doesn't exist return all the users

				if ($id === NULL)
				{
					// Check if the users data store contains users (in case the database result returns NULL)
					if ($users)
					{
						// Set the response and exit
						$this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
					}
					else
					{
						// Set the response and exit
						$this->response([
							'status' => FALSE,
							'message' => 'No users were found'
						], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
					}
				}

				// Find and return a single record for a particular user.
				else {
					$id = (int) $id;

					// Validate the id.
					if ($id <= 0)
					{
						// Invalid id, set the response and exit.
						$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
					}

					// Get the user from the array, using the id as key for retrieval.
					// Usually a model is to be used for this.

					$user = NULL;

					if (!empty($users))
					{
						foreach ($users as $key => $value)
						{
							if (isset($value['id']) && $value['id'] === $id)
							{
								$user = $value;
							}
						}
					}

					if (!empty($user))
					{
						$this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
					}
					else
					{
						$this->set_response([
							'status' => FALSE,
							'message' => 'User could not be found'
						], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
					}
				}
			   
			} catch (Exception $e) {
				$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
				$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
				
			}             
        }
		else
		{	

			$message = [
				'status'  =>  FALSE,
				'message' => 'No TOKEN'
		    ];
			$this->set_response($message, REST_Controller::HTTP_UNAUTHORIZED);
		}
		
    }

	
	 
    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];
		
		$headers = $this->input->request_headers();
		
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{			
			try {
			    
				$decoded 		= $this->token->validateToken($headers['Token']);
				$this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code

			} catch (Exception $e) {
				$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
				$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
				
			}
		}
		else
		{	

			$message = [
				'status'  =>  FALSE,
				'message' => 'No TOKEN'
		    ];
			$this->set_response($message, REST_Controller::HTTP_UNAUTHORIZED);
		}		
    }

	
	 
    public function users_delete()
    {
		
		$headers = $this->input->request_headers();
		
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{			
			try {
			    
				$decoded 		= $this->token->validateToken($headers['Token']);
				
				$id = (int) $this->get('id');

				
				// Validate the id.
				if ($id <= 0)
				{
					// Set the response and exit
					$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
				}

				// $this->some_model->delete_something($id);
				$message = [
				    'status'  =>  TRUE,
					'id' 	  => $id,
					'message' => 'Deleted the resource'
				];

				$this->set_response($message, REST_Controller::HTTP_CREATED); // 
				
			} catch (Exception $e) {
				$invalid = ['status'  =>  FALSE, 'message' => $e->getMessage()]; 
				$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400
				
			}	

		}
		else
		{	
			$message = [
				    'status'  =>  FALSE,
					'message' => 'No TOKEN'
				];
			$this->set_response($message, REST_Controller::HTTP_UNAUTHORIZED);
		}			

	  
    }
}