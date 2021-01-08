<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard_model extends CI_Model {

		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
		
	}
	
	function getData()
	{
		$sql="select a.*,DATE_FORMAT(a.order_date,'%d-%m-%Y %H:%i') update_date
from (select a.agenda_ins,b.INS_NAMINS, d.layanan_nama,count(c.nip) JUMLAH,
SUM(CASE 
	 WHEN c.nomi_status='BELUM' THEN 1
	 ELSE 0
   END) AS BELUM,
SUM(CASE 
	 WHEN c.nomi_status='ACC' THEN 1
	 ELSE 0
   END) AS ACC,
SUM(CASE 
	 WHEN c.nomi_status='BTL' THEN 1
	 ELSE 0
   END) AS BTL,
SUM(CASE 
	 WHEN c.nomi_status='TMS' THEN 1
	 ELSE 0
   END) AS TMS,  MAX(c.update_date) order_date
from paperless.agenda a
LEFT JOIN mirror.instansi b on a.agenda_ins = b.INS_KODINS
LEFT JOIN paperless.nominatif c ON a.agenda_id = c.agenda_id
LEFT JOIN paperless.layanan d ON a.layanan_id = d.layanan_id
where a.agenda_status='dikirim' 
AND DATE(a.agenda_timestamp) BETWEEN '2021-01-01' AND '2021-12-31'
group by a.agenda_ins,a.layanan_id ) a
order by a.order_date DESC";
		return $this->db->query($sql);
	}	
	
	
}