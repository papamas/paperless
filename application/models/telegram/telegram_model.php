<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Telegram_model extends CI_Model {

    var $table_user      = 'app_user';
	var $user_temp	     ='user_temp';
	var $menu_role       ='menu_role';
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	function _get_user_temp_by_nip($nip)
	{
		$this->db->select('username,password,email,first_name,last_name,nip,id_bidang,jabatan,id_instansi,gender,user_tipe');
		$this->db->where('nip',$nip);
		return $this->db->get($this->user_temp);		
	}	
	
	function _cekUserbynip($nip)
	{
		$this->db->where('nip',$nip);
		return $this->db->get($this->table_user);		
	}	
	
	function delete_usertemp_by_nip($nip)
	{
		$this->db->where('nip', $nip);
		return $this->db->delete($this->user_temp);
	}
	
	public function NonadminMember($data,$pesan)
	{
		$telegram_id		= $data['from']['id'];
		$nip				= trim($pesan[1]);		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 			
			
		if($this->isAdmin($telegram_id))
		{
			$this->db->set('is_admin','NULL',FALSE);
			$this->db->where('username',$nip);
			$this->db->update($this->table_user);
			
			if ($this->db->affected_rows() == 0)
			{
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
					$data['response'] 	= FALSE;
				}
				else
				{
					$data['pesan']		= " Tidak ada data yang terupdate untuk User dengan <strong>NIP : ".$nip."</strong>.";
					$data['response']	= FALSE;	
				}
					
			}
			else
			{
				$data['pesan']		= " User dengan <strong>NIP : ".$nip."</strong> sudah di set Sebagai Non Administrator.";
				$data['response']	= TRUE;				
			}	
        }	
		else
		{
			$data['pesan']		= ' Hanya Administrator yang diizinkan mengakses menu Non Admin Member';   
			$data['response'] 	= FALSE;
		}
		
		
		$this->db->db_debug = $db_debug; //restore setting				
		return $data;
	}		
	
	public function AdminMember($data,$pesan)
	{
		$telegram_id		= $data['from']['id'];
		$nip				= trim($pesan[1]);		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 			
			
		if($this->isAdmin($telegram_id))
		{
			$this->db->set('is_admin',1);
			$this->db->where('nip', $nip);
			$this->db->update($this->table_user);
			
			if ($this->db->affected_rows() == 0)
			{
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
					$data['response'] 	= FALSE;
				}
				else
				{
					$data['pesan']		= " Tidak ada data yang terupdate untuk User dengan <strong>NIP : ".$nip."</strong>.";
					$data['response']	= FALSE;	
				}
					
			}
			else
			{
				$data['pesan']		= " User dengan <strong>NIP : ".$nip."</strong> sudah di set Sebagai Administrator.";
				$data['response']	= TRUE;				
			}	
        }	
		else
		{
			$data['pesan']		= ' Hanya Administrator yang diizinkan mengakses menu Admin Member';   
			$data['response'] 	= FALSE;
		}
		
		
		$this->db->db_debug = $db_debug; //restore setting				
		return $data;
	}	
	
	public function BlokMember($data,$pesan)
	{
		$telegram_id		= $data['from']['id'];
		$id				    = trim($pesan[1]);		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 			
			
		if($this->isAdmin($telegram_id))
		{
			$this->db->set('active','NULL',FALSE);
			$this->db->where('user_id',$id);
			$this->db->or_where('nip', $id);
			$this->db->update($this->table_user);
			
			if ($this->db->affected_rows() == 0)
			{
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
					$data['response'] 	= FALSE;
				}
				else
				{
					$data['pesan']		= " Tidak ada data yang terupdate untuk User dengan <strong>UID/NIP : ".$id."</strong>.";
					$data['response']	= FALSE;	
				}
					
			}
			else
			{
				$data['pesan']		= " User dengan <strong>UID/NIP : ".$id."</strong> sudah di NON aktifkan.";
				$data['response']	= TRUE;				
			}	
        }	
		else
		{
			$data['pesan']		= " Hanya Administrator yang diizinkan mengakses menu BLOK Member";   
			$data['response'] 	= FALSE;
		}
		
		
		$this->db->db_debug = $db_debug; //restore setting				
		return $data;
	}	
	
	public function AktifMember($data,$pesan)
	{
		$telegram_id		= $data['from']['id'];
		$id				    = trim($pesan[1]);		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 			
			
		if($this->isAdmin($telegram_id))
		{
			$this->db->set('active',1);
			$this->db->where('user_id',$id);
			$this->db->or_where('nip', $id);
			$this->db->update($this->table_user);
			
			if ($this->db->affected_rows() == 0)
			{
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
					$data['response'] 	= FALSE;
				}
				else
				{
					$data['pesan']		= " Tidak ada data yang terupdate untuk User dengan <strong>UID/NIP : ".$id."</strong>.";
					$data['response']	= FALSE;	
				}
					
			}
			else
			{
				$data['pesan']		= " User dengan <strong>UID/NIP : ".$id."</strong> sudah diaktifkan.";
				$data['response']	= TRUE;				
			}	
        }	
		else
		{
			$data['pesan']		= " Hanya Administrator yang diizinkan mengakses menu AKTIF member";   
			$data['response'] 	= FALSE;
		}
		
		
		$this->db->db_debug = $db_debug; //restore setting				
		return $data;
	}	
	
	public function ApproveMember($data,$pesan)
	{
		$telegram_id		= $data['from']['id'];
		$nip				= trim($pesan[1]);		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = TRUE; 			
		
		$this->db->trans_begin();
		
		if($this->isAdmin($telegram_id))
		{
			$user_temp  = $this->_get_user_temp_by_nip($nip);
						
			if($user_temp->num_rows() > 0)
			{
				$this->db->insert_batch($this->table_user, $user_temp->result_array());
				$last_id 			= $this->db->insert_id();	
				$this->insert_menuInstansi($last_id);
				$this->delete_usertemp_by_nip($nip);
				
				if ($this->db->trans_status() === FALSE)
				{
					$data['pesan']		= " Failed Approve User";
					$data['response'] 	= FALSE;					
					$this->db->trans_rollback();			
				}
				else
				{
					$data['response']	= TRUE;
					$data['pesan']		= " Approve User dengan NIP ".$nip." telah Berhasil dengan <strong>UID : ".$last_id."</strong>, Silahkan lakukan aktifasi";		
					$this->db->trans_commit();
				}
			}
			else
			{
				$data['pesan']		= " Tidak Ada Member yang memerlukan Approve";   
			    $data['response'] 	= FALSE;
			}
        }	
		else
		{
			$data['pesan']		= " Hanya Administrator yang diizinkan mengakses menu APPROVE member";   
			$data['response'] 	= FALSE;
		}
		
		$this->db->db_debug = $db_debug; //restore setting				
		return $data;
	}	
	
	function insert_menuInstansi($id)
	{
		$data = array(
			array(
					'menu_id' => 3,
					'user_id' => $id,             
			),
			array(
					'menu_id' => 8,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 9,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 10,
					'user_id' => $id, 				   
			),
			array(
					'menu_id' => 11,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 12,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 13,
					'user_id' => $id,				   
			),			
		);

        return $this->db->insert_batch($this->menu_role, $data);
	}	
	
	function isAdmin($telegram_id)
	{
		$this->db->where('telegram_id',$telegram_id);
		$this->db->where('is_admin', 1);
		$app_user		= $this->db->get($this->table_user);
		
		$r  = FALSE;
		
		if($app_user->num_rows() > 0)
		{
		    $r  = TRUE;
		}	
		
		return $r;
	}	
	public function setTelegramAkun($data,$pesan)
	{
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 

		$telegram_id		= $data['from']['id'];
		$nip				= trim($pesan[1]);
			
		$cekUserbynip		= $this->_cekUserbynip($nip);
		
		if($cekUserbynip->num_rows() > 0) {
			$this->db->where('nip',$nip);
			$this->db->set('telegram_id',$telegram_id);   
			
			if ($this->db->update($this->table_user))
			{
				$data['pesan']		= "Akun Telegram Berhasil Tersimpan";
				$data['response']	= TRUE;           	
			}
			else
			{
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
					$data['response'] 	= FALSE;
				}
				
			}	
        }
		else
		{
			$data['pesan']		= " NIP tidak terdaftar" ;   
			$data['response'] 	= FALSE;
		}
		$this->db->db_debug = $db_debug; //restore setting	
		
		return $data;		
		
	}	
	
	public function getTelegramId($nip)
	{
	    $row			= $this->_cekUserbynip($nip)->row();
		return		    $row->telegram_id;	 
		
	}	
}