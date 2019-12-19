<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class dms extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();		
	    $this->db1   = $this->load->database('default', TRUE);
	} 
	
	public function index()
	{
		$this->load->library('Auth');		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$this->load->view('dms/vdms',$data);
	}
	
	public function search()
	{	   
		$this->load->library('Auth');
		$search            = $this->input->post('nip');
		
		if($search)
		{
			$sql="SELECT 
			a . *
		FROM
			okmdb.`okm_node_base` a
			INNER JOIN    okmdb.okm_node_folder b ON b.NBS_UUID = a.NBS_UUID
			WHERE a.NBS_CONTEXT ='okm_root' and a.NBS_NAME LIKE '%$search%'
			
			";
			$r = $this->db1->query($sql)->result_array();
			$children = array();
			if(count($r) > 0) 
			{
				# It has children, let's get them.
				foreach ( $r as $key => $item )
				{
					# Add the child to the list of children, and get its subchildren
					$children = $this->_getChildParent($item['NBS_UUID']);
					
				}
				$data['message'] = '';
			}
			else
			{
				$data['message'] = 'Sorry, File Not Found';
			}
			
			
			$data['children'] = $this->_buildFolder($children);
		
		}
		else
		{
		    $data['message'] = '';
		}
		
		$data['pupns']      = $this->_get_pupns($search);
		$data['pendidikan'] = $this->_get_pendidikan($search);	
        $data['unor']		= $this->_get_unorpns($search);	
		$data['kp']		    = $this->_getkp_info($search);	
		$data['pengadaan']  = $this->_get_pengadaan_info($search);
		$data['name']  		=  $this->auth->getName();
        $data['jabatan']    =  $this->auth->getJabatan();
		$data['member']	    =  $this->auth->getCreated();
		$data['avatar']	    =  $this->auth->getAvatar();
		$data['lname']    =  $this->auth->getLastName();    
		$this->load->view('dms/vdms',$data);
	
	}
	
	function _buildFolder($array)
	{
		 //if(count($array) == 1 ) return FALSE;
		 $html = "<ul class='tree'>";		  
		  foreach ($array as $item)
		  {
			$html .='<li> <span><i class="fa fa-folder-open"></i> ';				
			$html .=$item['NBS_NAME'].'</span>';
			
			if (!empty($item['CHILDREN']))
			{
			  $html .= $this->_buildFiles($item['CHILDREN']);
			}
			$html .='</span></li>';
			
		  }
		  $html .='</ul>';
		  
		  return $html;
	}
	
	function _buildFiles($array)
	{
		 //if(count($array) == 1 ) return FALSE;
		 $html = "<ul>";		  
		  foreach ($array as $item)
		  {
			$html .='<li> <span>';				
			$html .='<a href="#" class="file-preview" id="'.$item['NBS_UUID'].'">'.$item['NBS_NAME'].'</a></span>';
			
			if (!empty($item['CHILDREN']))
			{
			  $html .= $this->buildFiles($item['CHILDREN']);
			}
			$html .='</span></li>';
			
		  }
		  $html .='</ul>';
		  
		  return $html;
	}
	
	function _getChildParent($uuid) 	
	{
		$sql = "select 
		*
	from
		okmdb.okm_node_base
	WHERE
		nbs_parent = '$uuid' ORDER BY NBS_NAME ASC";
		$r = $this->db1->query($sql)->result_array();
		$children   = array();
		
		if(count($r) > 0) 
		{
		    foreach ( $r as $key => $item )
			{
			    $children[] = array( 'NBS_NAME' 	=> $item['NBS_NAME'],				
									 'NBS_UUID'		=> $item['NBS_UUID'],
									 'NBS_AUTHOR'   => $item['NBS_AUTHOR'],
									 'NBS_CREATED'  => $item['NBS_CREATED'],									
									 'CHILDREN'     => $this->_getChildParent($item['NBS_UUID']),
				);
				
				
				
			}
		}
				
		return $children;
		
	}
	
	public function getContent()
	{
	    $this->load->library('openkm');
		
		$uuid           = $this->uri->segment(3);
     	$this->openkm->Login('okmAdmin','admin');
		
		$respath        = $this->openkm->getPathDoc($uuid);
	    $path           = $respath['result'];
		$response     	= $this->openkm->getContent($path);
		$this->openkm->Logout();
		header('Pragma:public');
		header('Cache-Cont rol:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$uuid.'.pdf');                      
		header('Expires:0'); 
		header('Content-Transfer-Encoding: binary'); 
		echo $response['result'];   
	}

    function _get_pendidikan($search)
	{
	
	   $sql="SELECT a.*, b.*  FROM mirror.`pupns_pendidikan` a
LEFT JOIN mirror.pendik b ON a.PEN_PENKOD = b.DIK_KODIK
WHERE a.`PNS_NIPBARU` LIKE '$search'
ORDER BY a.`PEN_TAHLUL` DESC";

       $r = $this->db1->query($sql);
		
		return $r;
	}
	
	function _get_pupns($search)
	{
	
	    $sql="SELECT a.*,DATE_FORMAT(a.PNS_TGLLHRDT,'%d-%m-%Y') LAHIR,
		DATE_FORMAT(a.PNS_TMTCPN,'%d-%m-%Y') CPNS,
		DATE_FORMAT(a.PNS_TMTPNS,'%d-%m-%Y') PNS,
		DATE_FORMAT(a.PNS_TMTGOL,'%d-%m-%Y') TMTGOL,
		b.GOL_GOLNAM,b.GOL_PKTNAM, c.DIK_NAMDIK, d.LOK_LOKNAM,
		e.KED_KEDNAM, f.GOL_GOLNAM GOL_AWAL , g.JPG_JPGNAM,
		h.INS_NAMINS INSDUK , i.INS_NAMINS INSKER, j.JJB_JJBNAM
		FROM mirror.pupns a 
		LEFT JOIN mirror.golru b ON a.PNS_GOLRU =b.GOL_KODGOL
		LEFT JOIN mirror.tktpendik c ON a.PNS_TKTDIK = c.DIK_TKTDIK
		LEFT JOIN mirror.lokker  d ON  a.PNS_TEMKRJ = d.LOK_LOKKOD
		LEFT JOIN mirror.kedhuk e ON a.PNS_KEDHUK = e.KED_KEDKOD
		LEFT JOIN mirror.golru f ON a.PNS_GOLAWL = f.GOL_KODGOL
		LEFT JOIN mirror.jenpeg g ON a.PNS_JENPEG = g.JPG_JPGKOD
		LEFT JOIN mirror.instansi h ON a.PNS_INSDUK = h.INS_KODINS
		LEFT JOIN mirror.instansi i ON a.PNS_INSKER = i.INS_KODINS
		LEFT JOIN mirror.jenjab j ON a.PNS_JNSJAB = j.JJB_JJBKOD
		WHERE a.PNS_NIPBARU LIKE '$search'";
		$r = $this->db1->query($sql);
		
		if($r->num_rows() > 0)
		{
			
			$r = $r->row();
		}
		else
		{
			
			$r = array();
		}	
		
		return $r;
	}
	
	function _get_unorpns($search)
	{
	
	    $sql="SELECT 
    a.`PNS_NIPBARU`,
    a.`PNS_PNSNAM`,
    h.INS_NAMINS INSDUK,
    j.JJB_JJBNAM,
    k.JBF_NAMJAB,
    l.UNO_NAMUNO,
    l.UNO_NAMJAB,
    l.UNO_DIATASAN_ID,
    m.UNO_NAMUNO UNO_INDUK
FROM
    mirror.pupns a
        LEFT JOIN
    mirror.instansi h ON a.PNS_INSDUK = h.INS_KODINS
        LEFT JOIN
    mirror.jenjab j ON a.PNS_JNSJAB = j.JJB_JJBKOD
        LEFT JOIN
    mirror.jabfun k ON a.PNS_JABFUN = k.JBF_KODJAB
		LEFT JOIN
    mirror.unor l ON (a.PNS_UNITOR = l.UNO_KODUNO
        AND a.PNS_INSDUK = l.UNO_INSTAN
        AND a.PNS_UNOR = l.UNO_ID)
    LEFT join
    mirror.unor m ON (l.UNO_DIATASAN_ID = m.UNO_ID  AND a.PNS_INSDUK = m.UNO_INSTAN) 
WHERE
    a.PNS_NIPBARU='$search'";
		$r = $this->db1->query($sql);
		
		if($r->num_rows() > 0)
		{
			
			$r = $r->row();
		}
		else
		{
			
			$r = array();
		}	
		
		return $r;
	}	
	
	function _getkp_info($search)
	{
	   $sql="select * from (select a.*,b.PNS_TEMKRJ, c.GOL_GOLNAM GOL_BARU, d.GOL_GOLNAM GOL_LAMA , e.JKP_JPNNAMA FROM (
	   SELECT JKP_JPNKOD,PKI_NIPBARU,NOTA_PERSETUJUAN_KP ,PKI_SK_TANGGAL,
	   DATE_FORMAT(TGL_NOTA_PERSETUJUAN_KP,'%d-%m-%Y') TGL_NOTA_PERSETUJUAN_KP,
	   DATE(PKI_TMT_GOLONGAN_BARU) PKI_TMT_GOLONGAN_BARU ,
	   PKI_GOLONGAN_LAMA_ID,PKI_GOLONGAN_BARU_ID FROM mirror.pupns_kp_info 
	   WHERE PKI_NIPBARU='$search' AND NOTA_PERSETUJUAN_KP IS NOT NULL 
	   ) a 
	   INNER JOIN mirror.pupns b ON b.PNS_NIPBARU = a. PKI_NIPBARU
	   LEFT JOIN mirror.golru  c ON a.PKI_GOLONGAN_BARU_ID = c.GOL_KODGOL
	   LEFT JOIN mirror.golru  d ON a.PKI_GOLONGAN_LAMA_ID = d.GOL_KODGOL
	   LEFT JOIN mirror.jenis_kp e ON a.JKP_JPNKOD = e.JKP_JPNKOD
	   ) a ORDER BY PKI_SK_TANGGAL DESC";
	   
	    $r = $this->db1->query($sql);
		
		return $r;
	}
	
	function _get_pengadaan_info($search)
	{
	    $sql ="SELECT a.*,DATE_FORMAT(a.TMT_CPNS,'%d-%m-%Y') CPNS,
		DATE_FORMAT(a.PERSETUJUAN_TEKNIS_TANGGAL,'%d-%m-%Y') TANGGAL_TEKNIS,
		DATE_FORMAT(a.DITETAPKAN_TANGGAL,'%d-%m-%Y') TANGGAL_PENETAPAN
		FROM mirror.pupns_pengadaan_info  a WHERE a.NIP LIKE '$search' ";
		$r = $this->db1->query($sql);
		if($r->num_rows() > 0)
		{
			
			$r = $r->row();
		}
		else
		{
			
			$r = array();
		}	
		
		return $r;
		
		
	}
}
