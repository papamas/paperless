<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">  
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />
	
  </head> 
	
	<style>
    #mySidenav a {
	  position: fixed; 	 
	  transition: 0.5s; 
	  padding: 18px;
	  width: 300px; 
	  text-decoration: none;
	  font-size: 12px; 
	  color: white; 
	  border-radius: 0 5px 5px 0; 
	  z-index:2;
	}

	#mySidenav a:hover {
	  left: -250px;
	}

	
	#about {
	  top:65px;
	  background-color: #4CAF50;
	}
	
	#info {
	  top:120px;
	  background-color: red;
	}

    #spinner-modal .modal-dialog,
    #spinner-modal .modal-content,
    #spinner-modal .modal-body {
        background: transparent;
        color: rgba(255,255,255,1);
        box-shadow: none;
        border: none;
    }

</style>
  </head>
  <body class="hold-transition skin-yellow sidebar-collapse">
  <div class="wrapper">	
	<header class="main-header">
	    <?php echo $this->load->view('vlogo');?>

        <nav class="navbar navbar-static-top" >
		    <!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
			  </a>
		  
	        <div class="container">
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<i class="fa fa-bars"></i>
				  </button>
				</div>	   
				
				<div class="navbar-custom-menu"> 
				 <ul class="nav navbar-nav">
					<li class="dropdown user user-menu">
						<!-- Menu Toggle Button -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						  <!-- The user image in the navbar-->
						  <img src="<?php echo base_url()?>assets/dist/img/<?php echo $avatar?>" class="user-image" alt="User Image">					  
						  <!-- hidden-xs hides the username on small devices so only the image appears. -->
						  <i class="fa fa-circle text-success faa-flash animated" title="online"></i><span class="hidden-xs "></a>
						<ul class="dropdown-menu">
						  <!-- The user image in the menu -->
						  <li class="user-header">
							<img src="<?php echo base_url()?>assets/dist/img/<?php echo $avatar?>" class="img-circle" alt="User Image">
							<p >
							  <?php echo $name?> <br/> <?php echo $jabatan?>
							  <small>Member since -  <?php echo $member;?></small>
							</p>
							
						  </li>
						  <!-- Menu Body 
						  <li class="user-body">
							<div class="col-xs-4 text-center">
							  <a href="#">Followers</a>
							</div>
							<div class="col-xs-4 text-center">
							  <a href="#">Sales</a>
							</div>
							<div class="col-xs-4 text-center">
							  <a href="#">Friends</a>
							</div>
						  </li>
						  -->
						  <!-- Menu Footer-->
						  <li class="user-footer">
							<div class="pull-left">
							  <a href="<?php echo site_url()?>/profile/" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
							  <a href="<?php echo site_url()?>/autho/logout/" class="btn btn-default btn-flat">Sign out</a>
							</div>
						  </li>
						</ul>
					  </li>
				</ul>	
				</div>
						
				<div class="collapse navbar-collapse" id="navbar-collapse">			
					<form class="navbar-form"  method="POST" action="<?php echo site_url()?>/verifikator/verifyPost">
					   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						<div class="form-group" style="display:inline;">
						  <div class="input-group" style="display:table;">
							<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
							<input class="form-control" pattern="[0-9]{18}" maxlength="18" required name="nip" placeholder="Masukan NIP..." autocomplete="on" autofocus="autofocus" type="text">
						  </div>
						</div>						
					 </form>			
				</div><!--/.nav-collapse --> 			
		    </div><!-- container-->			
		</nav>
	</header>
	<!-- Left side column -->
        <?php echo $this->load->view('vleft-side');?>
       <!-- End Left side column -->
	<?php 
	if($usul->num_rows() > 0){		
	    $row = $usul->row();
		
		if(!empty($row->nomi_alasan))
		{	
			echo '<div id="mySidenav" class="sidenav">
				<a href="#" id="about">'.$row->layanan_nama.'-'.$row->agenda_nousul.'<br/> Tahap : '.$row->tahapan_nama.'
				<br/>Alasan : '.$row->nomi_alasan.'</a>  
			</div>';
		}
		else
		{
            echo '<div id="mySidenav" class="sidenav">
				<a href="#" id="about">'.$row->layanan_nama.'-'.$row->agenda_nousul.'<br/> Tahap : '.$row->tahapan_nama.'</a>  
			</div>';
		}			
		// jika layanan KARIS/KARSU/KARPEG/PEREMAJAAN DATA main dokumen dari pengantar
		if($row->layanan_id == 9 || $row->layanan_id == 10 || $row->layanan_id == 11 || $row->layanan_id == 14 || $row->layanan_id == 20)
		{
			// abaikan main upload
		}
		else
		{
			
			if(empty($row->main_upload_dokumen)){
				echo '<div id="mySidenav" class="sidenav">
					<a href="#" id="info">Tidak ada Dokumen Usul</a>  
				</div>';
			}	
		}	
	}	
    ?>
	<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
	    <!-- Main content -->
        <section class="content ">
		    <?php if($usul->num_rows() > 0) :?>
			<?php 
			$row 	 = $usul->row();
					
			$tipe    = $this->session->userdata('session_user_tipe');
			if($row->layanan_id == 9 || $row->layanan_id == 10 || $row->layanan_id == 11 || $row->layanan_id == 14 || $row->layanan_id == 20)
			{
				if($row->locked_by == $this->session->userdata('user_id'))
				{
					$target = '#kerjaModal';
				}
				else
				{
					// jika tipe 2 = kabid , tipe 3 kanreg
					// tetap bisa verifikasi walau sudah terkunci
					if($tipe  == 2 || $tipe == 3)
					{	
						$target = '#kerjaModal';
					}
					else
					{
						$target = '#';
					}						
				}
				echo '<div class="zoom">
						<a class="zoom-fab zoom-btn-large" id="zoomBtn"><i class="fa fa-bars"></i></a>
						<ul class="zoom-menu">
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-feedback scale-transition scale-out" data-tooltip="tooltip" data-placement="top" title="'.$row->tahapan_nama.'"><i class="fa fa-bell"></i></a></li>
						  <li><a id="kerja" class="zoom-fab zoom-btn-sm zoom-btn-person scale-transition scale-out" data-toggle="modal" data-target="'.($row->nomi_locked == '1' ? $target : '#kerjaModal').'" data-tooltip="tooltip" data-placement="top" title="'.($row->nomi_locked == '1' ? 'Berkas telah di kunci oleh '.$row->lock_name :  'Kerjakan berkas Layanan '.$row->layanan_nama.' atas nama '.$row->nama).'"><i id="fa-user" class="fa fa-user"></i></a></li>
						  <li><a id="verifikasi" class="hidden zoom-fab zoom-btn-sm zoom-btn-doc scale-transition scale-out" data-toggle="modal" data-target="#verifikasiModal" data-tooltip="tooltip" data-placement="top" title="" data-original-title="Hasil Verifikasi berkas ASN atas nama '.$row->nama.'"><i class="fa fa-book"></i></a></li>
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out"><i class="fa fa-edit"></i></a></li>
						  
						  
						</ul>				
					</div>';	
			}
			else
			{		
				
				if(!empty($row->main_upload_dokumen)){
					if($row->locked_by == $this->session->userdata('user_id'))
					{
						$target = '#kerjaModal';
					}
					else
					{
						// jika tipe 2 = kabid , tipe 3 kanreg
						// tetap bisa verifikasi walau sudah terkunci
						if($tipe  == 2 || $tipe == 3)
						{	
							$target = '#kerjaModal';
						}
						else
						{
							$target = '#';
						}						
					}
					
					
					$zoom ='<div class="zoom">
						<a class="zoom-fab zoom-btn-large" id="zoomBtn"><i class="fa fa-bars"></i></a>
						<ul class="zoom-menu">
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-feedback scale-transition scale-out" data-tooltip="tooltip" data-placement="top" title="'.$row->tahapan_nama.'"><i class="fa fa-bell"></i></a></li>
						  <li><a id="kerja" class="zoom-fab zoom-btn-sm zoom-btn-person scale-transition scale-out" data-toggle="modal" data-target="'.($row->nomi_locked == '1' ? $target : '#kerjaModal').'" data-tooltip="tooltip" data-placement="top" title="'.($row->nomi_locked == '1' ? 'Berkas telah di kunci oleh '.$row->lock_name :  'Kerjakan berkas Layanan '.$row->layanan_nama.' atas nama '.$row->nama).'"><i id="fa-user" class="fa fa-user"></i></a></li>
						  <li><a id="verifikasi" class="hidden zoom-fab zoom-btn-sm zoom-btn-doc scale-transition scale-out" data-toggle="modal" data-target="#verifikasiModal" data-tooltip="tooltip" data-placement="top" title="" data-original-title="Hasil Verifikasi berkas ASN atas nama '.$row->nama.'"><i class="fa fa-book"></i></a></li>';
						  
							if($row->layanan_id == 19)
							{  
								$zoom .='<li><a id="epmk" data-toggle="modal" data-agenda="'.$row->agenda_id.'" data-nip="'.$row->nip.'" data-target="#epmkModal" data-tooltip="tooltip" data-placement="top" data-original-title="Lihat Nota Usul PMK" class="hidden zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out"><i class="fa fa-edit"></i></a></li>';
						    }
							
						  $zoom .='<li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-tangram scale-transition scale-out"><i class="fa fa-dashboard"></i></a></li>';			  
						  
						$zoom .='</ul>				
					</div>';	
					
					echo $zoom;
				}
			}	
			?>
			<?php endif;?>
			<div class="row">
			    <div class="col-md-6 p-0 h-md-100 nopadding">
				    <?php 
					
					if($usul->num_rows() > 0){
					    $row = $usul->row();
						// jika layanan KARPEG/KARIS/KARSU/PENCATUMAN GELAR main dokumen dari pengantar
						if($row->layanan_id == 9 || $row->layanan_id == 10 || $row->layanan_id == 11  || $row->layanan_id == 14 || $row->layanan_id == 20)
						{
							echo '<embed src="'.site_url().'/verifikator/getFilePengantar/?id='.$this->myencrypt->encode($row->agenda_ins).'&f='.$this->myencrypt->encode($row->agenda_dokumen).'" type="application/pdf" width="100%" height="100%">';					

                        }
						else
						{
							echo '<embed src="'.site_url().'/verifikator/getFile/?id='.$this->myencrypt->encode($row->agenda_ins).'&f='.$this->myencrypt->encode($row->main_upload_dokumen).'" type="application/pdf" width="100%" height="100%">';					
                        }
					}
					else
					{
					   
							echo '<div class="box box-warning">
									<div class="callout callout-warning">
									<h4>Warning!</h4>
								<p>Oops ! Maaf berkas tidak ditemukan.</p>
								</div></div>';
							
					}
                    ?>
				</div>	

			
				
				<div id="berkas" class="col-md-6 nopadding hidden">                 
                            <div class="panel"><div class="panel-body">
							<?php 
							if($tabs->num_rows() > 0)
							{
							    echo '<ul class="nav nav-pills">';
								echo '<li class=""><a href="#pnsData" data-toggle="tab">CPNS/PNS</a></li>';
							    foreach($tabs->result() as $value){
									$jenis_sk = $value->nama_dokumen;
									
								if( $jenis_sk === "SK_JABATAN" || $jenis_sk === "PAK" || $jenis_sk === "IJAZAH" || $jenis_sk === "SKP"  || $jenis_sk === "PPK" || $jenis_sk === "SK_KP"  || $jenis_sk === "SK_MUTASI" || $jenis_sk === "TRANSKRIP" || $jenis_sk === "STLUD" || $jenis_sk === "HONOR" ) {									
									echo '<li role="presentation" class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$value->nama_dokumen.'<b class="caret"></b></a>									
										<ul class="dropdown-menu">.';	
										    
											$x = explode(",",$value->grup_dok);	
											$y = explode(",",$value->upload_raw_name);
											rsort($x);
											rsort($y);
											
                                            if($jenis_sk != "IJAZAH" && $jenis_sk != "TRANSKRIP") 
											{											
												for($i=0;$i < count($y);$i++){	
													switch($x[$i]){
														case 45:
															$n = "IV/e";
														break;
														case 44:
															$n = "IV/d";
														break;
														case 43:
															$n = "IV/c";
														break;
														case 42:
															$n = "IV/b";
														break;
														case 41:
															$n = "IV/a";
														break;
														case 34:
															$n = "III/d";
														break;
														case 33:
															$n = "III/c";
														break;
														case 32:
															$n = "III/b";
														break;
														case 31:
															$n = "III/a";
														break;
														case 24:
															$n = "II/d";
														break;
														case 23:
															$n = "II/c";
														break;
														case 22:
															$n = "II/b";
														break;
														case 21:
															$n = "II/a";
														break;
														case 14:
															$n = "I/d";
														break;
														case 13:
															$n = "I/c";
														break;
														case 12:
															$n = "I/b";
														break;
														case 11:
															$n = "I/a";
														break;
														case 1:
															$n = "Tk.I";
														break;
														case 2:
															$n = "Tk.II";
														break;
														case 3:
															$n = "PI";
														break;
														default:
															$n = $x[$i];									
																									
													}					
													
													echo '<li><a href="#'.$this->myencrypt->encode($y[$i]).'" role="tab" data-toggle="tab">'.$n.'</a></li>';
												}
												
											}
											else
											{
												
												for($i=0;$i < count($y);$i++){	
													switch($x[$i]){
														case 50:
															$n = "S-3/Doktor";
														break;
														case 45:
															$n = "S-2";
														break;
														case 40:
															$n = "S-1/Sarjana";
														break;
														case 35:
															$n = "Diploma IV";
														break;
														case 30:
															$n = "Diploma III/Sarjana Muda";
														break;
														case 25:
															$n = "Diploma II";
														break;
														case 20:
															$n = "Diploma I";
														break;
														case 18:
															$n = "SLTA Keguruan";
														break;
														case 17:
															$n = "SLTA Kejuruan";
														break;
														case 15:
															$n = "SLTA";
														break;
														case 12:
															$n = "SLTP Kejuruan";
														break;
														case 10:
															$n = "SLTP";
														break;
														case 15:
															$n = "Sekolah Dasar";
														break;														
														default:
															$n = $x[$i];									
																									
													}
													echo '<li><a href="#'.$this->myencrypt->encode($y[$i]).'" role="tab" data-toggle="tab">'.$n.'</a></li>';
												}
													
											}
											
										echo '</ul></li>';
									}else{
										echo '<li class=""><a href="#'.$this->myencrypt->encode($value->raw_name).'" data-toggle="tab">'.$value->nama_dokumen.'</a></li>';
									}								 
                                }
								
								echo '</ul>';
							}
											
							?>

                            <div class="tab-content">
							    <div class="tab-pane fade p-0 h-md-100 " id="pnsData">
								   <div class="box box-widget">
									<!-- Add the bg color to the header using any of the bg-* classes -->
									<?php   if(1==0):  ?>
									<?php $rowPnsDataOracle = $pnsDataOracle->row();?>
										 <div class="box-footer">
											<ul class="nav nav-stacked">
											<li>Nama<span class="pull-right"><?php echo (!empty($rowPnsDataOracle->GELAR_DEPAN) ? $rowPnsDataOracle->GELAR_DEPAN : '').' '.$rowPnsDataOracle->NAMA.''.(!empty($rowPnsDataOracle->GELAR_BLK) ? ','.$rowPnsDataOracle->GELAR_BLK : '')?></span></li>
											<li>NIP<span class="pull-right"><?php echo $rowPnsDataOracle->NIP_BARU?></span></li>
											<li>Status Kedudukan Hukum<span class="pull-right"><?php echo $rowPnsDataOracle->NAMA_KEDUDUKAN_HUKUM?></span></li>
											<li>Status Kepegawaian<span class="pull-right"><?php echo ($rowPnsDataOracle->STATUS_CPNS_PNS == 'P' ? 'PNS' : 'CPNS')?></span></li>
											<li>TMT CPNS<span class="pull-right"><?php echo $rowPnsDataOracle->TMT_CPNS?></span></li>
											<li>Nomor SK CPNS<span class="pull-right"><?php echo $rowPnsDataOracle->NOMOR_SK_CPNS?></span></li>
											<li>Tanggal SK CPNS<span class="pull-right"><?php echo $rowPnsDataOracle->TGL_SK_CPNS?></span></li>
											<li>Nomor Urut SK CPNS<span class="pull-right"><?php echo $rowPnsDataOracle->NOM_URUT_SK_CPNS?></span></li>
											<li>Pejabat yang mengangkat CPNS<span class="pull-right"><?php echo $rowPnsDataOracle->SPESIMEN_PEJABAT_CPNS?></span></li>
											<li>TMT PNS<span class="pull-right"><?php echo $rowPnsDataOracle->TMT_PNS?></span></li>
											<li>Nomor SK PNS<span class="pull-right"><?php echo $rowPnsDataOracle->NOMOR_SK_PNS?></span></li>
											<li>Tanggal SK PNS<span class="pull-right"><?php echo $rowPnsDataOracle->TGL_SK_PNS?></span></li>
											<li>Nomor Urut SK PNS<span class="pull-right"><?php echo $rowPnsDataOracle->NOM_URUT_SK_PNS?></span></li>
											<li>Nomor STTPL <span class="pull-right"><?php echo $rowPnsDataOracle->NOMOR_STTPL?></span></li>
											<li>Tanggal STTPL <span class="pull-right"><?php echo $rowPnsDataOracle->TGL_STTPL?></span></li>
											<li>Nomor Dokter PNS <span class="pull-right"><?php echo $rowPnsDataOracle->NOMOR_DOKTER_PNS?></span></li>
											<li>Tanggal Dokter PNS <span class="pull-right"><?php echo $rowPnsDataOracle->TANGGAL_DOKTER_PNS?></span></li>					
											<li>Nomor SPMT <span class="pull-right"><?php echo $rowPnsDataOracle->NOMOR_SPMT?></span></li>
											<li>Tanggal SPMT <span class="pull-right"><?php echo $rowPnsDataOracle->TGL_TUGAS?></span></li>
                                            <li>Unit Organisasi<span class="pull-right"><?php echo $rowPnsDataOracle->NAMA_UNOR?></span></li>
											<li>Instansi Induk<span class="pull-right"><?php echo $rowPnsDataOracle->NAMA_INSTANSI_INDUK?></span></li>											
											<li>Satuan Kerja Induk<span class="pull-right"><?php echo $rowPnsDataOracle->NAMA_SATUAN_KERJA_INDUK?></span></li>											
											<li>Instansi Kerja<span class="pull-right"><?php echo $rowPnsDataOracle->NAMA_INSTANSI_KERJA?></span></li>						
											<li>Satuan Kerja<span class="pull-right"><?php echo $rowPnsDataOracle->NAMA_SATUAN_KERJA?></span></li>						

										  </ul>
										</div>
									<?php endif; ?>	
									</div>		
                                </div>   
							    <?php if($dokumen->num_rows() > 0):?>
								<?php foreach($dokumen->result() as $value):?>
                                <div class="tab-pane fade p-0 h-md-100 " id="<?php echo $this->myencrypt->encode($value->raw_name)?>">
                                  <embed src="<?php echo site_url()."/verifikator/getFile/?id=".$this->myencrypt->encode($value->id_instansi)."&f=".$this->myencrypt->encode($value->orig_name)?>"  type="application/pdf" width="100%" height="100%" >
                                </div>                                				
								<?php endforeach;?>  
							
								<?php endif;?>
                            </div>
                        </div>
                    </div>
				</div>
			</div><!-- /.row -->
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<!-- Modal -->
	<?php 
	if($usul->num_rows() > 0){		
	    $row = $usul->row();
        if($row->locked_by == $this->session->userdata('user_id'))
		{
			$m = '<p>Anda Ingin Mengerjakan Berkas ini? </p>';
		}
		else
		{
			$m = '<p>Setelah anda konfirmasi maka berkas akan dikunci dan hanya ada yang boleh membukanya, Anda Ingin Mengerjakan Berkas ini? </p>';
		}		
		echo '<div class="modal fade" id="kerjaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
					</div>
					<div class="modal-body">
						<form id="nfrmKerja">
                          <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'" style="display: none">
						  <div class="form-group">'.$m.
							
							'<input class="form-control" type="hidden" value="'.$row->agenda_id.'" name="id_agenda" />	
							<input class="form-control" type="hidden" value="'.$row->nip.'" name="nip" />
							<input class="form-control" type="hidden" value="'.$row->layanan_id.'" name="layanan_id">
						  </div>				  
						</form>
					 </div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" id="nBtnKerja">OK</button>
					</div>
				</div>
			</div>	
		</div>';
	}
	?>
	<!-- Modal -->
	<?php 
	    if($usul->num_rows() > 0){		
	    $row 		 	 = $usul->row();
		$layanan_id  	 = $row->layanan_id;
		$bidang          = $this->session->userdata('session_bidang');
		
		$tipe        = $this->session->userdata('session_user_tipe');
		$check       = "";
		
		if($tipe == 2)
		{
		    if($layanan_id == 13 || $layanan_id == 14 || $layanan_id == 20)
			{
				// jika layanan pindah instansi sembunyikan konfirmasi berkas finish
				$hidden = "hidden";	
				// jika layanan id peningkatan peningkatan dan pindah instansi  finish hanya sampai eselon 3
				$check  =" checked ";  
						
			}
			else
			{	
				$hidden = "";
			}
		}
		else
		{
            $hidden  ="hidden";
        }	
		
		if($bidang == 2)
		{
			$kpp_hidden = " ";			
		}
		else
		{
			$kpp_hidden = "hidden";		
		}
		echo '<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
					</div>
					<div class="modal-body">
						<form id="nfrmVerifikasi">
						   <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'" style="display: none">
						  <div class="form-group">
							<label for="status">Hasil Verifikasi</label>
							<select name="status" class="form-control" required>
								<option value="">--</option>
								<option value="ACC">ACC</option>
								<option value="BTL">BTL</option>
								<option value="TMS">TMS</option>
							</select>
						  </div>
						  <div class="form-group">
							<label for="catatan">Catatan</label>
							<textarea name="catatan" class="form-control"></textarea>
						  </div>
						    <div class="form-group '.$hidden.'">
								<label class="form-check-label text-red">
									<input type="checkbox" value="1" '.$check.' name="finish">&nbsp;Klik disini jika berkas ini selesai verifikasi sampai Level 2									
								</label>	
							</div>
							
							<div class="form-group '.$kpp_hidden.'">
								<label class="control-label">Berkas ini pensiun KPP :</label>
								<input type="radio" value="1" name="kpp_status" />&nbsp;Ya
								<input type="radio" value="2" name="kpp_status"  checked/>&nbsp;Tidak
							</div>
                            <input class="form-control" type="hidden" value="'.$row->agenda_id.'" name="id_agenda" />	
							<input class="form-control" type="hidden" value="'.$row->nip.'" name="nip" />
							<input class="form-control" type="hidden" value="'.$row->layanan_id.'" name="layanan_id">	
							<input class="form-control" type="hidden" value="'.$row->PNS_GOLRU.'" name="golongan">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn bg-maroon" id="nBtn">Simpan</button>
					</div>
				</div>
			</div>	
		</div>';
	}
	?>
	
	<div class="modal fade" id="epmkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="efrmPmk">
						<input class="form-control" type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>">
		                <input class="form-control" type="hidden" value="" name="nip" />
						<input class="form-control" type="hidden" value="" name="agendaId" />

						
						 <!-- Custom Tabs -->
						  <div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
							  <li class="active"><a href="#tab_1" data-toggle="tab">Utama</a></li>
							  <li><a href="#tab_2" data-toggle="tab">Perhitungan</a></li>
							  <li><a href="#tab_3" data-toggle="tab">Ijazah</a></li>
							  <li><a href="#tab_4" data-toggle="tab">Salinan Bukti-Bukti</a></li>
							  <li><a href="#tab_5" data-toggle="tab">SK Pangkat</a></li>
							</ul>
							<div class="tab-content">
							  <div class="tab-pane active" id="tab_1">
								<table class="table table-bordered ">
									<tr>
										<td colspan="2">Tempat Lahir</td>
										<td colspan="4"><input class="form-control" type="text" placeholder="Tempat Lahir" name="tempatLahir"></td>
									</tr>
									<tr>
										<td rowspan="4" width="5px">LAMA</td>
										<td width="300px">1. MASA KERJA GOL</td>
										<td colspan="2">						
										<input class="form-control" type="text" placeholder="Tahun" name="oldTahun">
										</td>
										<td colspan="2">						
										<input class="form-control " type="text" placeholder="Bulan" name="oldBulan"></td>
									</tr>
									<tr>
										<td>2. GAJI POKOK</td>
										<td colspan="4"><input class="form-control" type="text" placeholder="Gaji Pokok" name="oldGaji"></td>
									</tr>
									<tr>
										<td>3. SEJAK</td>
										<td colspan="4">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
												<input id="tmtGaji" class="form-control" type="text" placeholder="TMT Gaji" name="oldTmtGaji">
											</div>	
										</td>
									</tr>
									<tr>
										<td>4. PERSETUJUAN BKN</td>
										<td colspan="2"><input class="form-control" type="text" placeholder="Nomor Persetujuan" name="nomorPersetujuan"></td>
										<td colspan="2">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											<input id='tmtAg' class="form-control" type="text" placeholder="Tanggal Persetujuan" name="tanggalPersetujuan">
											</div>
										</td>		
									</tr>
									<tr>
										<td rowspan="3"  width="20px" align="center">BARU</td>
										<td>1. MASA KERJA GOL</td>
										<td><input readonly class="form-control" type="text" placeholder="Tahun" name="baruTahun"></td>
										<td><input class="form-control" type="text" placeholder="Tahun ACC" name="baruTahunAcc"></td>
										<td><input readonly class="form-control" type="text" placeholder="Bulan" name="baruBulan"></td>
										<td><input class="form-control" type="text" placeholder="Bulan ACC" name="baruBulanAcc"></td>
									</tr>
									<tr>
										<td>2. GAJI POKOK</td>
										<td colspan="2"><input readonly class="form-control" type="text" placeholder="Gaji Pokok" name="baruGaji"></td>
										<td colspan="2"><input class="form-control" type="text" placeholder="Gaji Pokok ACC" name="baruGajiAcc"></td>
										
									</tr>
									<tr>
										<td>BERLAKU TERHITUNG MULAI TANGGAL</td>
										<td colspan="2">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											<input id="baruTmt" readonly class="form-control" type="text" placeholder="TMT" name="baruTmtGaji">
											</div>
										</td>
										<td colspan="2">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											<input id="baruTmtAcc" class="form-control" type="text" placeholder="TMT ACC" name="baruTmtGajiAcc">
											</div>
										</td>										
									</tr>
								</table>	
							  </div>
							  <!-- /.tab-pane -->
							  <div class="tab-pane" id="tab_2">
								<table class="table table-bordered ">
									<tr>
										<td rowspan="3"  width="20px" align="center">LAMA</td>
										<td width="150px" rowspan="2" align="center"> PENGALAMAN KERJA</td>
										<td width="160px" align="center" rowspan="2" colspan="2">MULAI DAN SAMPAI DENGAN TGL. BL. TH</td>
										<td  align="center" colspan="2" width="60px">JUMLAH</td>
										<td  align="center" width="10px">DINILAI</td>
										<td  align="center" colspan="2" width="60px">JUMLAH</td>
									</tr>
									<tr>
										<td  align="center">TH</td>
										<td  align="center">BL</td>
										<td  align="center"></td>
										<td  align="center">TH</td>
										<td  align="center">BL</td>
									</tr>
									<tr>
										<td width="160px"  align="center"> DIANGKAT SEBAGAI HONORER</td>
										<td width="160px" align="center">
										  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										  <input id="mulaiHonor" class="form-control" type="text" placeholder="MULAI" name="mulaiHonor"></div>
										</td>  
										<td width="160px">
											 <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										   <input class="form-control" type="text" placeholder="SAMPAI" id="sampaiHonor" name="sampaiHonor"></div>
										</td>
										<td align="center">
										   <input readonly class="form-control" type="text" placeholder="TH" name="tahunHonor">
										</td>
										<td align="center">
										   <input readonly class="form-control" type="text" placeholder="BL" name="bulanHonor">
										</td>
										<td></td>
										<td align="center">
										   <input class="form-control" type="text" placeholder="TH" name="dinilaiTahunHonor">
										</td>
										<td align="center">
										   <input class="form-control" type="text" placeholder="BL" name="dinilaiBulanHonor">
										</td>
										
									</tr>
									<tr>
										<td rowspan="3"  width="20px" align="center">BARU</td>
										<td width="150px" rowspan="3" align="center"> DIANGKAT SEBAGAI CALON PEGAWAI</td>
										<td  align="center" rowspan="3"> 
										   <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
										   <input class="form-control" type="text" placeholder="MULAI" id="mulaiPegawai" name="mulaiPegawai"></div>
										</td>  
										<td rowspan="3">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										   <input class="form-control" type="text" placeholder="SAMPAI" id="sampaiPegawai" name="sampaiPegawai"></div>
										</td>
										<td  rowspan="3" align="center" >
										  <input readonly class="form-control" type="text" placeholder="TH" name="tahunPegawai">
										</td>
										<td  rowspan="3" align="center" >
										   <input readonly class="form-control" type="text" placeholder="BL" name="bulanPegawai">
										</td>
										<td></td>
										<td  rowspan="3" align="center" >
										  <input class="form-control" type="text" placeholder="TH" name="dinilaiTahunPegawai">
										</td>
										<td  rowspan="3" align="center" >
										   <input class="form-control" type="text" placeholder="BL" name="dinilaiBulanPegawai">
										</td>
										
									</tr>
									<tr></tr>
									<tr></tr>
									<tr>
										<td width="20px" align="center">KET</td>
										<td width="150px" colspan="8"> <input class="form-control" type="text" placeholder="KETERANGAN" name="keterangan"></td>
									</tr>
									
									
								</table>	
							  </div>
							  <!-- /.tab-pane -->
							  <div class="tab-pane" id="tab_3">
								<table class="table table-bordered ">
									<tr>
									   <td>A.</td>
									   <td colspan="3">STTB/Ijazah/Diploma/Akta</td>
									</tr>
									<tr>
									   <td>1.</td>
									   <td> 
										  <select class="form-control" name="tingkat1">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah1"></td>
									   <td> 
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
												<input id="tanggalIjazah1" class="form-control" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah1">
											</div>
										</td>
									</tr>
									<tr>
									   <td>2.</td>
									   <td> 
										  <select class="form-control" name="tingkat2">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah2"></td>
									   <td> 
										<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id="tanggalIjazah2" class="form-control" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah2"></td>
										</div>
									</tr>
									<tr>
									   <td>3.</td>
									   <td> 
										  <select class="form-control" name="tingkat3">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah3"></td>
									   <td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" id="tanggalIjazah3" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah3"></div></td>
									</tr>
									<tr>
									   <td>4.</td>
									   <td> 
										  <select class="form-control" name="tingkat4">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah4"></td>
									   <td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" id="tanggalIjazah4" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah4"></div></td>
									</tr>
									<tr>
									   <td>5.</td>
									   <td> 
										  <select class="form-control" name="tingkat5">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah5"></td>
									   <td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input id="tanggalIjazah5" class="form-control" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah5"></div></td>
									</tr>
								</table>	
							  </div>
								<div class="tab-pane" id="tab_4">
								   <table class="table table-bordered ">
										<tr>
										   <td>C.</td>
										   <td>Salinan Sah dan bukti-bukti pengalaman kerja</td>
										</tr>
										<tr>
										   <td></td>
										   <td>
											 <textarea class="form-control" placeholder="Salinan sah bukti-bukti" name="salinanSah"></textarea>
										   </td>
										</tr>
								   </table>
								</div>
								<div class="tab-pane" id="tab_5">
									<table class="table table-bordered ">
										<tr>
										   <td>D.</td>
										   <td>Surat Keputusan</td>
										</tr>
										<tr>
										   <td></td>
										   <td>
											 <textarea class="form-control" placeholder="Surat Keputusan Kenaikan Pangkat" name="skPangkat"></textarea>
										   </td>
										</tr>
								   </table>
								</div>
								 <!-- /.tab-pane -->							 	
							</div>
							<!-- /.tab-content -->
						  </div>
						  <!-- nav-tabs-custom -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnAccPmk">Simpan</button>
				</div>
			</div>
		</div>	
	</div>
    	
	<!--[ SPINNER MODAL ]-->
	<div class="modal fade" id="spinner-modal">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body text-center">
				    <img src="<?php echo base_url()?>/assets/dist/img/loading.gif" alt="waiting..." />
					<h3><i class="fa fa-cog fa-spin"></i> Working...</h3>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>	
	<script>	
	$(document).ready(function () {
		
		$("#nBtnAccPmk").on("click",function(e){
			e.preventDefault();			
			var data = $('#efrmPmk').serialize();
					
			$('#epmkModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/saveAccPmk",
				data: data,
				dataType:'json',
				success: function(e){
					$('#epmkModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
							 
				}, 
				error : function(e){
					$('#epmkModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('#sampaiPegawai').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#mulaiPegawai').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#sampaiHonor').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#mulaiHonor').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#baruTmtAcc').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tmtGaji').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tmtAg').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
        
		$("input[name=nip]").on('keyup', function (e) {
			event.preventDefault();			
			if (e.keyCode === 13) {
				
				console.log($("input[name=nip]").val());
			}
		});
		
		$('#epmkModal').on('show.bs.modal',function(e){
			
			var agenda=  $(e.relatedTarget).attr('data-agenda');
			var nip   =  $(e.relatedTarget).attr('data-nip');
			
		    $('#epmkModal #msg').text('Nota Usul PMK') 
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 			
			
			$("input[name=agendaId]").val(agenda);
			$("input[name=nip]").val(nip);
			
			$.ajax({
				type: "GET",
				url : "<?php echo site_url()?>/verifikator/getUsul",
				data: {agendaId:agenda,nip:nip},
				dataType:'json',
				success: function(r){
					$('#epmkModal input[name=tempatLahir]').val(r.tempat_lahir);	
					$('#epmkModal input[name=oldTahun]').val(r.old_masa_kerja_tahun);	
                    $('#epmkModal input[name=oldBulan]').val(r.old_masa_kerja_bulan);
					$('#epmkModal input[name=oldGaji]').val(r.old_gaji_pokok);
					$('#epmkModal input[name=oldTmtGaji]').val(r.old_tmt_gaji);
					$('#epmkModal input[name=nomorPersetujuan]').val(r.nomor_persetujuan);
					$('#epmkModal input[name=tanggalPersetujuan]').val(r.tanggal_persetujuan);
					$('#epmkModal input[name=baruTahun]').val(r.baru_masa_kerja_tahun);
					$('#epmkModal input[name=baruBulan]').val(r.baru_masa_kerja_bulan);
					$('#epmkModal input[name=baruGaji]').val(r.baru_gaji_pokok);
					$('#epmkModal input[name=baruTmtGaji]').val(r.baru_tmt_gaji);
					$('#epmkModal input[name=mulaiHonor]').val(r.mulai_honor);
					$('#epmkModal input[name=sampaiHonor]').val(r.sampai_honor);
					$('#epmkModal input[name=tahunHonor]').val(r.tahun_honor);
					$('#epmkModal input[name=bulanHonor]').val(r.bulan_honor);
					$('#epmkModal input[name=mulaiPegawai]').val(r.mulai_pegawai);
					$('#epmkModal input[name=sampaiPegawai]').val(r.sampai_pegawai);
					$('#epmkModal input[name=tahunPegawai]').val(r.tahun_pegawai);
					$('#epmkModal input[name=bulanPegawai]').val(r.bulan_pegawai);
					$('#epmkModal [name=salinanSah]').val(r.salinan_sah);
					$('#epmkModal [name=skPangkat]').val(r.sk_pangkat);
					
					$('#epmkModal [name=tingkat1]').val(r.tingkat1);
					$('#epmkModal input[name=nomorIjazah1]').val(r.nomor_ijazah1);
					$('#epmkModal input[name=tanggalIjazah1]').val(r.tanggal_ijazah1);
					
					$('#epmkModal [name=tingkat2]').val(r.tingkat2);
					$('#epmkModal input[name=nomorIjazah2]').val(r.nomor_ijazah2);
					$('#epmkModal input[name=tanggalIjazah2]').val(r.tanggal_ijazah2);
					
					$('#epmkModal [name=tingkat3]').val(r.tingkat3);
					$('#epmkModal input[name=nomorIjazah3]').val(r.nomor_ijazah3);
					$('#epmkModal input[name=tanggalIjazah3]').val(r.tanggal_ijazah3);
					
					$('#epmkModal [name=tingkat4]').val(r.tingkat4);
					$('#epmkModal input[name=nomorIjazah4]').val(r.nomor_ijazah4);
					$('#epmkModal input[name=tanggalIjazah4]').val(r.tanggal_ijazah4);
					
					$('#epmkModal [name=tingkat5]').val(r.tingkat5);
					$('#epmkModal input[name=nomorIjazah5]').val(r.nomor_ijazah5);
					$('#epmkModal input[name=tanggalIjazah5]').val(r.tanggal_ijazah5);
					
					$('#epmkModal input[name=baruTahunAcc]').val(r.acc_masa_kerja_tahun);
					$('#epmkModal input[name=baruBulanAcc]').val(r.acc_masa_kerja_bulan);
					$('#epmkModal input[name=baruGajiAcc]').val(r.acc_gaji_pokok);
					$('#epmkModal input[name=baruTmtGajiAcc]').val(r.acc_tmt_gaji);
					
					$('#epmkModal input[name=dinilaiTahunHonor]').val(r.dinilai_tahun_honor);
					$('#epmkModal input[name=dinilaiBulanHonor]').val(r.dinilai_bulan_honor);
					$('#epmkModal input[name=dinilaiTahunPegawai]').val(r.dinilai_tahun_pegawai);
					$('#epmkModal input[name=dinilaiBulanPegawai]').val(r.dinilai_bulan_pegawai);
					
					$('#epmkModal input[name=keterangan]').val(r.keterangan);
				},
			});	
		});
		
		
		$('#kerjaModal').on('show.bs.modal',function(event){
		     $('#kerjaModal #msg').text('Konfirmasi Pengerjaan Berkas')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
		});
		
		
		$("#nBtnKerja").on("click",function(e){
			e.preventDefault();
			
			var data = $('#nfrmKerja').serialize();
			var div = $("#verifikasi");
			var berkas = $("#berkas");
			var kerja = $("#kerja #fa-user");
		    var pmk = $("#epmk");
			
			$('#kerjaModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/kerja",
				data: data,
				success: function(){
					$('#kerjaModal #msg').text('Berkas telah dikunci, selamat bekerja..')
                             .removeClass( "text-blue")
				             .addClass( "text-green" ); 
							 
					div.removeClass('hidden').addClass("visible"); 		
					berkas.removeClass('hidden').addClass("visible"); 
					pmk.removeClass('hidden').addClass("visible"); 
					kerja.removeClass('fa fa-user').addClass("fa fa-lock"); 
					
				}, // akhir fungsi sukses
		    });
			return false;
		});
		
		$('[data-tooltip="tooltip"]').tooltip();

		$('#verifikasiModal').on('hide.bs.modal',function(event){
			$("#nBtn").show();
		});	
		
		$('#verifikasiModal').on('show.bs.modal',function(event){
			$("#nBtn").show();
		});	
		
		$('#verifikasiModal').on('show.bs.modal',function(event){
		    $('#verifikasiModal #msg').text('Hasil Verifikasi Berkas') 
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			$('[name=status]').val('');
			$('[name=catatan]').val('');
		});
		
		$("#nBtn").on("click",function(e){
			e.preventDefault();
		    $('#verifikasiModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
					 
			var data = $('#nfrmVerifikasi').serialize();
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/save",
				data: data,
				success: function(){
					$('#verifikasiModal #msg').text('Hasil verifikasi berkas telah berhasil disimpan, silahkan anda close dialog ini')
                             .removeClass( "text-blue")
				             .addClass( "text-green" );					
					$("#nBtn").hide();
				}, 
				error : function(r) {				    
					 $('#verifikasiModal #msg').text(r.responseJSON.error)
                     .removeClass( "text-green")
					 .removeClass( "text-blue")
				     .addClass( "text-red" ); 
				},
				beforeSend: function () {
                   $('#spinner-modal').modal('show');	 
                },
                complete: function () {
                    $('#spinner-modal').modal('hide');
                }	
		    });
			return false;
		});
		
		
	    $('#zoomBtn').click(function() {
		  $('.zoom-btn-sm').toggleClass('scale-out');
		  if (!$('.zoom-card').hasClass('scale-out')) {
			$('.zoom-card').toggleClass('scale-out');
		  }
		});

		$('.zoom-btn-sm').click(function() {
		  var btn = $(this);
		  var card = $('.zoom-card');

		  if ($('.zoom-card').hasClass('scale-out')) {
			$('.zoom-card').toggleClass('scale-out');
		  }
		  if (btn.hasClass('zoom-btn-person')) {
			card.css('background-color', '#d32f2f');
		  } else if (btn.hasClass('zoom-btn-doc')) {
			card.css('background-color', '#fbc02d');
		  } else if (btn.hasClass('zoom-btn-tangram')) {
			card.css('background-color', '#388e3c');
		  } else if (btn.hasClass('zoom-btn-report')) {
			card.css('background-color', '#1976d2');
		  } else {
			card.css('background-color', '#7b1fa2');
		  }
		});

		
	});	
   </script>
  </body>
</html>
