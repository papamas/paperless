<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Peminjaman_model extends CI_Model {

    var $table_instansi = 'mirror.instansi';
	var $table_pns      = 'mirror.pupns';
	
    function __construct()
    {
        parent::__construct();
		$this->db1 = $this->load->database('default', TRUE);
    }
		
	public function getInstansi($kode_instansi)
	{
	   	$this->db1->like('INS_NAMINS',$kode_instansi);	
		return $this->db1->get($this->table_instansi); 
	}
	
	public function getPns($nip)
    {
        $this->db1->select("PNS_NIPBARU id");
		$this->db1->select("CONCAT( PNS_NIPBARU ,' - ', PNS_PNSNAM) text",FALSE);
		$this->db1->like('PNS_NIPBARU',$nip);
		return $this->db1->get($this->table_pns); 
    }	
	
}