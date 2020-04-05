<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Telegram_model extends CI_Model {

    var $table_user      = 'app_user';
	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	function _cekUserbynip($nip)
	{
		$this->db->where('nip',$nip);
		return $this->db->get($this->table_user);		
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