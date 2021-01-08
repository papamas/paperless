<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
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
		// jika layanan KARPEG main dokumen dari pengantar
		if($row->layanan_id == 9 || $row->layanan_id == 10 || $row->layanan_id == 11)
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
			if($row->layanan_id == 9 || $row->layanan_id == 10 || $row->layanan_id == 11)
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
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-tangram scale-transition scale-out"><i class="fa fa-dashboard"></i></a></li>
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
					echo '<div class="zoom">
						<a class="zoom-fab zoom-btn-large" id="zoomBtn"><i class="fa fa-bars"></i></a>
						<ul class="zoom-menu">
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-feedback scale-transition scale-out" data-tooltip="tooltip" data-placement="top" title="'.$row->tahapan_nama.'"><i class="fa fa-bell"></i></a></li>
						  <li><a id="kerja" class="zoom-fab zoom-btn-sm zoom-btn-person scale-transition scale-out" data-toggle="modal" data-target="'.($row->nomi_locked == '1' ? $target : '#kerjaModal').'" data-tooltip="tooltip" data-placement="top" title="'.($row->nomi_locked == '1' ? 'Berkas telah di kunci oleh '.$row->lock_name :  'Kerjakan berkas Layanan '.$row->layanan_nama.' atas nama '.$row->nama).'"><i id="fa-user" class="fa fa-user"></i></a></li>
						  <li><a id="verifikasi" class="hidden zoom-fab zoom-btn-sm zoom-btn-doc scale-transition scale-out" data-toggle="modal" data-target="#verifikasiModal" data-tooltip="tooltip" data-placement="top" title="" data-original-title="Hasil Verifikasi berkas ASN atas nama '.$row->nama.'"><i class="fa fa-book"></i></a></li>
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-tangram scale-transition scale-out"><i class="fa fa-dashboard"></i></a></li>
						  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out"><i class="fa fa-edit"></i></a></li>
						  
						</ul>				
					</div>';	
				}
			}	
			?>
			<?php endif;?>
			<div class="row">
			    <div class="col-md-6 p-0 h-md-100 nopadding">
				    <?php 
					
					if($usul->num_rows() > 0){
					    $row = $usul->row();
						// jika layanan KARPEG main dokumen dari pengantar
						if($row->layanan_id == 9 || $row->layanan_id == 10 || $row->layanan_id == 11)
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
									
								if( $jenis_sk === "SK_JABATAN" || $jenis_sk === "PAK" || $jenis_sk === "IJAZAH" || $jenis_sk === "SKP"  || $jenis_sk === "PPK" || $jenis_sk === "SK_KP"  || $jenis_sk === "SK_MUTASI" || $jenis_sk === "TRANSKRIP" || $jenis_sk === "STLUD" ) {									
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
									<?php/*  if(1==0): */?>
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
									<?php /* endif; */?>	
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
		    if($layanan_id == 13 || $layanan_id == 14)
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
	<script>	
	$(document).ready(function () {
        
		$("input[name=nip]").on('keyup', function (e) {
			event.preventDefault();			
			if (e.keyCode === 13) {
				
				console.log($("input[name=nip]").val());
			}
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
