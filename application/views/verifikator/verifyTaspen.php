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
					<form class="navbar-form"  method="POST" action="<?php echo site_url()?>/verifikator/verifyPostTaspen">
					   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						<div class="form-group" style="display:inline;">
						  <div class="input-group" style="display:table;">
							<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
							<input class="form-control" required name="nip" placeholder="Masukan NIP..." autocomplete="on" autofocus="autofocus" type="text">
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
		
		if(!empty($row->usul_alasan))
		{	
			echo '<div id="mySidenav" class="sidenav">
				<a href="#" id="about">'.$row->layanan_nama.'-'.$row->nomor_usul.'<br/> Tahap : '.$row->tahapan_nama.'
				<br/>Alasan : '.$row->usul_alasan.'</a>  
			</div>';
		}
		else
		{
            echo '<div id="mySidenav" class="sidenav">
				<a href="#" id="about">'.$row->layanan_nama.'-'.$row->nomor_usul.'<br/> Tahap : '.$row->tahapan_nama.'</a>  
			</div>';
		}			
		
		
        if(empty($row->main_upload_dokumen)){
			echo '<div id="mySidenav" class="sidenav">
				<a href="#" id="info">Tidak ada Dokumen Usul</a>  
			</div>';
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
			if(!empty($row->main_upload_dokumen)){
				if($row->usul_lock_by == $this->session->userdata('user_id'))
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
					  <li><a id="kerja" class="zoom-fab zoom-btn-sm zoom-btn-person scale-transition scale-out" data-toggle="modal" data-target="'.($row->usul_locked == 1 ? $target : '#kerjaModal').'" data-tooltip="tooltip" data-placement="top" title="'.($row->usul_locked == '1' ? 'Berkas telah di kunci oleh '.$row->usul_lock_name :  'Kerjakan berkas Layanan '.$row->layanan_nama.' atas nama '.$row->nama_pns).'"><i id="fa-user" class="fa fa-user"></i></a></li>
					  <li><a id="verifikasi" class="hidden zoom-fab zoom-btn-sm zoom-btn-doc scale-transition scale-out" data-toggle="modal" data-target="#verifikasiModal" data-tooltip="tooltip" data-placement="top" title="" data-original-title="Hasil Verifikasi berkas '.$row->nama_pns.'"><i class="fa fa-book"></i></a></li>
					  <li><a href="#draft" id="?u='.$this->myencrypt->encode($row->usul_id).'&l='.$this->myencrypt->encode($row->layanan_id).'&n='.$this->myencrypt->encode($row->nip).'" class="hidden zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out" data-tooltip="tooltip" data-placement="top" data-original-title="Cetak Draft SK '.$row->nama_pns.'"><i class="fa fa-print"></i></a></li>
					  <li><a class="hidden zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out"><i class="fa fa-edit"></i></a></li>
					  
					</ul>				
				</div>';	
			}	
			?>
			<?php endif;?>
			<div class="row">
			    <div class="col-md-6 p-0 h-md-100 nopadding">
				    <?php 
					
					if($usul->num_rows() > 0){
					    $row = $usul->row();
				        echo '<embed src="'.site_url().'/verifikator/getFileTaspen/?id='.$this->myencrypt->encode($row->usul_id).'&f='.$this->myencrypt->encode($row->main_upload_dokumen).'&t='.$this->myencrypt->encode('application/pdf').' " width="100%" height="100%">';					
                    }
					else
					{
					   
							echo '<div class="box box-warning">
									<div class="callout callout-warning">
									<h4>Warning!</h4>
								<p>Oops ! Maaf berkas yang anda tidak ditemukan.</p>
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
								if($usul->num_rows() > 0){		
	                               $row = $usul->row();
								   echo '<li class=""><a href="#DataAnak" data-usul="'.$row->usul_id.'" data-layanan="'.$row->layanan_id.'" data-toggle="tab" data-tooltip="tooltip" title="Lihat Data Anak" >Data Anak</a></li>';
								    if($row->layanan_id == 15)
								    {
										echo '<li class=""><a href="#DataIstri" data-usul="'.$row->usul_id.'" data-toggle="tab" data-tooltip="tooltip" title="Lihat Data Istri" >Data Istri/Suami</a></li>';
                                    }
								}
								
							    foreach($tabs->result() as $value){
									echo '<li class=""><a href="#'.$this->myencrypt->encode($value->raw_name).'" data-toggle="tab" data-tooltip="tooltip" title="'.$value->keterangan.'" >'.$value->nama_dokumen.'</a></li>';
																	 
                                }								
								echo '</ul>';
							}
											
							?>

                            <div class="tab-content">
							    <div class="tab-pane fade p-0 h-md-100" id="DataAnak">
									<div class="row">
										<h3 class="page-header text-red">NAMA ANAK(2)KANDUNG </h3>	
																	
									</div>	
									<div class="table-responsive">
										<table id="tb-anak"  class="table table-striped table-condensed">
										
										</table>
									</div>	
								</div>								
							    <div class="tab-pane fade p-0 h-md-100" id="DataIstri">
									<h3 class="page-header text-red">ISTRI(2)SUAMI</h3>								
									<div class="table-responsive">
										<table id="tb-istri"  class="table table-striped table-condensed">
										
										</table>
									</div>	
								</div>
							    <?php if($dokumen->num_rows() > 0):?>
								<?php foreach($dokumen->result() as $value):?>
                                <div class="tab-pane fade p-0 h-md-100 " id="<?php echo $this->myencrypt->encode($value->raw_name)?>">
                                  <embed src="<?php echo site_url()."/verifikator/getFileTaspen/?f=".$this->myencrypt->encode($value->orig_name)."&t=".$this->myencrypt->encode($value->file_type)?>"  width="100%" height="100%" >
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
        if($row->usul_lock_by == $this->session->userdata('user_id'))
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
							
							'<input class="form-control" type="hidden" value="'.$row->usul_id.'" name="usul_id" />	
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
	    $row 		 = $usul->row();
		$layanan_id  = $row->layanan_id;
		
		$tipe        = $this->session->userdata('session_user_tipe');
		
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
                            <input class="form-control" type="hidden" value="'.$row->usul_id.'" name="usul_id" />	
							<input class="form-control" type="hidden" value="'.$row->nip.'" name="nip" />
							<input class="form-control" type="hidden" value="'.$row->layanan_id.'" name="layanan_id">	
							
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
	<div id="anakModalJd" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="anakFormJd">
					    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        <input class="form-control" type="hidden" value="" name="jd_dd_anak_id" />
						<input class="form-control" type="hidden" value="" name="usul_id" />
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Nama</label>							
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama" />	
                            </div>
							<label class="col-md-2 control-label">Tgl Lahir</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_lahir" value="<?php echo (set_value('tgl_lahir') ? set_value('tgl_lahir') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>
							</div>
						</div>
						<div class="form-group row">						
							<label class="col-md-2 control-label">Nama Ayah</label>
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama_ayah" />	
                            </div>	
							<label class="col-md-2 control-label">Nama Ibu</label>
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama_ibu" />	
                            </div>	
						</div>
						
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Keterangan</label>
							<div class="col-md-4">
								<input class="form-control" type="text" value="AK" name="keterangan" readonly />	
                            </div>	
						</div>
						
                    </form>
                </div> 
                <div class="modal-footer">
				   <button type="button" class="btn btn-primary" id="nBtnAnakJd">Simpan</button>
				</div>				
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="hapusAnakModalJd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmHapusAnakJd">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin akan menghapus ?</div>
                       <input type="hidden" name="jd_dd_anak_id"/>				   					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapusAnakJd"><i class="fa fa-leaf"></i>&nbsp;OK Hapus !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div id="anakModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="anakForm">
					    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        <input class="form-control" type="hidden" value="" name="temp_mutasi_id" />
						<input class="form-control" type="hidden" value="" name="usul_id" />
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Nama</label>							
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama" />	
                            </div>
							<label class="col-md-2 control-label">Sex</label>
							<div class="col-md-4">
								<select name="sex" class="form-control">
									<option value="">--silahkan Pilih--</option>
									<option value="LK">Laki-Laki</option>
									<option value="PR">Perempuan</option>									
								</select>	
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Tgl Lahir</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_lahir" value="<?php echo (set_value('tgl_lahir') ? set_value('tgl_lahir') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>
							</div>
							<label class="col-md-2 control-label">Nama Ibu/Ayah</label>
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama_ibu_ayah" />	
                            </div>	
						</div>
						
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Tgl Cerai</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_cerai" value="" class="form-control datetimepicker" />
								</div>								
                            </div>
							<label class="col-md-2 control-label">Tgl Wafat</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_wafat" value="" class="form-control datetimepicker" />
								</div>							
                            </div>
						</div>
						
                    </form>
                </div> 
                <div class="modal-footer">
				   <button type="button" class="btn btn-primary" id="nBtnAnak">Simpan</button>
				</div>				
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="hapusAnakModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmHapusAnak">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin akan menghapus ?</div>
                       <input type="hidden" name="temp_mutasi_id"/>				   					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapusAnak"><i class="fa fa-leaf"></i>&nbsp;OK Hapus !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div id="istriModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="istriForm">
					    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        <input class="form-control" type="hidden" value="" name="temp_mutasi_id" />
						<input class="form-control" type="hidden" value="" name="usul_id" />
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Nama</label>							
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama" />	
                            </div>
							<label class="col-md-2 control-label">Nama Kecil</label>
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="nama_kecil" />	
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Tempat Lahir</label>
							<div class="col-md-4">
								<input class="form-control" type="text" value="" name="tempat_lahir" />	
                            </div>
							<label class="col-md-2 control-label">Tgl Lahir</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_lahir" value="<?php echo (set_value('tgl_lahir') ? set_value('tgl_lahir') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>
							</div>
						</div>
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Tgl Nikah</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_nikah" value="<?php echo (set_value('tgl_nikah') ? set_value('tgl_nikah') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>									
                            </div>
							<label class="col-md-2 control-label">Tgl Pendaftaran</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_pendaftaran" value="<?php echo (set_value('tgl_pendaftaran') ? set_value('tgl_pendaftaran') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>								
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Tgl Cerai</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_cerai" value="" class="form-control datetimepicker" />
								</div>								
                            </div>
							<label class="col-md-2 control-label">Tgl Wafat</label>
							<div class="col-md-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input type='text' name="tgl_wafat" value="" class="form-control datetimepicker" />
								</div>							
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-md-2 control-label">Alamat</label>
							<div class="col-md-10">
								<input class="form-control" type="text" value="" name="alamat" />	
                            </div>							
						</div>
                    </form>
                </div> 
                <div class="modal-footer">
				   <button type="button" class="btn btn-primary" id="nBtnIstri">Simpan</button>
				</div>				
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="hapusIstriModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmHapusIstri">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin akan menghapus ?</div>
                       <input type="hidden" name="temp_mutasi_id"/>				   					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapusIstri"><i class="fa fa-leaf"></i>&nbsp;OK Hapus !</button>
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
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>	
	<script>	
	$(document).ready(function () {
        
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('.datetimepicker').datetimepicker({
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
			var draft = $("a[href='#draft']");
			var kerja = $("#kerja #fa-user");
			
			$('#kerjaModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/kerjaTaspen",
				data: data,
				success: function(){
					$('#kerjaModal #msg').text('Berkas telah dikunci, selamat bekerja..')
                             .removeClass( "text-blue")
				             .addClass( "text-green" ); 
							 
					div.removeClass('hidden').addClass("visible"); 		
					berkas.removeClass('hidden').addClass("visible"); 
					draft.removeClass('hidden').addClass("visible");
					kerja.removeClass('fa fa-user').addClass("fa fa-lock"); 
					
				}, // akhir fungsi sukses
		    });
			return false;
		});
		
		$('[data-tooltip="tooltip"]').tooltip();


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
				url : "<?php echo site_url()?>/verifikator/saveTaspen",
				data: data,
				success: function(){
					$('#verifikasiModal #msg').text('Hasil verifikasi berkas berhasil disimpan.....')
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

		$('a[href="#draft"]').click(function(){
			var id= this.id;
		    document.location = "<?php echo site_url()?>/verifikator/draftTaspen/"+id;
		}); 
		
		$('a[href="#DataAnak"]').on("click",function(e){			
			var usul_id    =  $(this).attr('data-usul')
				layanan    =  $(this).attr('data-layanan');
				
			if(layanan == 16){		
				$('#anakModalJd input[name=usul_id]').val(usul_id);
				refreshAnakJd();
			}
			else{
				$('#anakModal input[name=usul_id]').val(usul_id);
				refreshAnak();
			}
		});	
		
		$('a[href="#DataIstri"]').on("click",function(e){			
			var usul_id    =  $(this).attr('data-usul');
			$('#istriModal input[name=usul_id]').val(usul_id);
		    refreshIstri();
		});
		
		$('#anakModalJd').on('show.bs.modal',function(e){
		     $('#anakModalJd #msg').text('Penambahan Data Anak Kandung')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id				=  $(e.relatedTarget).attr('data-id'),
				nama    		=  $(e.relatedTarget).attr('data-nama'),
				tgl_lahir       =  $(e.relatedTarget).attr('data-tgl_lahir'),				
				nama_ibu     	=  $(e.relatedTarget).attr('data-ibu'),
				nama_ayah     	=  $(e.relatedTarget).attr('data-ayah'),
				usul_id		    =  $(e.relatedTarget).attr('data-usul');
			
			$('#anakModalJd input[name=jd_dd_anak_id]').val(id);
			$('#anakModalJd input[name=nama]').val(nama);
			$('#anakModalJd input[name=tgl_lahir]').val(tgl_lahir);			
			$('#anakModalJd input[name=nama_ayah]').val(nama_ayah);
			$('#anakModalJd input[name=nama_ibu]').val(nama_ibu);
			$('#anakModalJd input[name=usul_id]').val(usul_id);
			
		});
		
		$("#nBtnAnakJd").on("click",function(e){
			e.preventDefault();			
			var data = $('#anakFormJd').serialize();
					
			$('#anakModalJd #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/simpanAnakJd",
				data: data,
				dataType:'json',
				success: function(e){
					$('#anakModalJd #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshAnakJd();		 
				}, 
				error : function(e){
					$('#anakModalJd #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('#hapusAnakModalJd').on('show.bs.modal',function(e){
		     $('#hapusAnakModalJd #msg').text('Konfirmasi Delete Anak')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id		=  $(e.relatedTarget).attr('data-id');			
			$('#hapusAnakModalJd input[name=jd_dd_anak_id]').val(id);
			
		});
		
		$("#nBtnHapusAnakJd").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmHapusAnakJd').serialize();
			
			$('#hapusAnakModalJd #msg').text('Deleting Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/hapusAnakJd",
				data: data,
				success: function(){					
					$('#hapusAnakModalJd #msg').text('Berhasil menghapus data....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshAnakJd();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
		$('#anakModal').on('show.bs.modal',function(e){
		     $('#anakModal #msg').text('Penambahan Data Anak')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id				=  $(e.relatedTarget).attr('data-id'),
				nama    		=  $(e.relatedTarget).attr('data-nama'),
				sex      		=  $(e.relatedTarget).attr('data-sex'),				
				tgl_lahir       =  $(e.relatedTarget).attr('data-tgl_lahir'),				
				tgl_cerai		      =  $(e.relatedTarget).attr('data-tgl_cerai'),
				tgl_wafat		      =  $(e.relatedTarget).attr('data-tgl_wafat'),
				nama_ibu_ayah      	  =  $(e.relatedTarget).attr('data-nama_ibu_ayah'),
				usul_id		      	  =  $(e.relatedTarget).attr('data-usul');
			
			$('#anakModal input[name=temp_mutasi_id]').val(id);
			$('#anakModal input[name=nama]').val(nama);
			$('#anakModal [name=sex]').val(sex);			
			$('#anakModal input[name=tgl_lahir]').val(tgl_lahir);			
			$('#anakModal input[name=tgl_cerai]').val(tgl_cerai);			
			$('#anakModal input[name=tgl_wafat]').val(tgl_wafat);
			$('#anakModal input[name=nama_ibu_ayah]').val(nama_ibu_ayah);
			$('#anakModal input[name=usul_id]').val(usul_id);
			
		});
		
		$("#nBtnAnak").on("click",function(e){
			e.preventDefault();			
			var data = $('#anakForm').serialize();
					
			$('#anakModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/simpanAnak",
				data: data,
				dataType:'json',
				success: function(e){
					$('#anakModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshAnak();		 
				}, 
				error : function(e){
					$('#anakModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('#hapusAnakModal').on('show.bs.modal',function(e){
		     $('#hapusAnakModal #msg').text('Konfirmasi Delete Anak')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id		=  $(e.relatedTarget).attr('data-id');			
			$('#hapusAnakModal input[name=temp_mutasi_id]').val(id);
			
		});
		
		$("#nBtnHapusAnak").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmHapusAnak').serialize();
			
			$('#hapusAnakModal #msg').text('Deleting Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/hapusAnak",
				data: data,
				success: function(){					
					$('#hapusAnakModal #msg').text('Berhasil menghapus data....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshAnak();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
		$('#istriModal').on('show.bs.modal',function(e){
		     $('#istriModal #msg').text('Penambahan Data Istri')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id				=  $(e.relatedTarget).attr('data-id'),
				nama    		=  $(e.relatedTarget).attr('data-nama'),
				nama_kecil      =  $(e.relatedTarget).attr('data-nama_kecil'),
				tempat_lahir    =  $(e.relatedTarget).attr('data-tempat_lahir'),
				tgl_lahir       =  $(e.relatedTarget).attr('data-tgl_lahir'),
				tgl_nikah       =  $(e.relatedTarget).attr('data-tgl_nikah'),
				tgl_pendaftaran       =  $(e.relatedTarget).attr('data-tgl_pendaftaran'),
				tgl_cerai		      =  $(e.relatedTarget).attr('data-tgl_cerai'),
				tgl_wafat		      =  $(e.relatedTarget).attr('data-tgl_wafat'),
				alamat		      	  =  $(e.relatedTarget).attr('data-alamat'),
				usul_id		      	  =  $(e.relatedTarget).attr('data-usul');
			
			$('#istriModal input[name=temp_mutasi_id]').val(id);
			$('#istriModal input[name=nama]').val(nama);
			$('#istriModal input[name=nama_kecil]').val(nama_kecil);
			$('#istriModal input[name=tempat_lahir]').val(tempat_lahir);
			$('#istriModal input[name=tgl_lahir]').val(tgl_lahir);
			$('#istriModal input[name=tgl_nikah]').val(tgl_nikah);
			$('#istriModal input[name=tgl_cerai]').val(tgl_cerai);
			$('#istriModal input[name=tgl_pendaftaran]').val(tgl_pendaftaran);
			$('#istriModal input[name=tgl_wafat]').val(tgl_wafat);
			$('#istriModal input[name=alamat]').val(alamat);
			$('#istriModal input[name=usul_id]').val(usul_id);
		});		
		
		
		$("#nBtnIstri").on("click",function(e){
			e.preventDefault();			
			var data = $('#istriForm').serialize();
					
			$('#istriModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/simpanIstri",
				data: data,
				dataType:'json',
				success: function(e){
					$('#istriModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshIstri();		 
				}, 
				error : function(e){
					$('#istriModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('#hapusIstriModal').on('show.bs.modal',function(e){
		     $('#hapusIstriModal #msg').text('Konfirmasi Delete Istri')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id		=  $(e.relatedTarget).attr('data-id');			
			$('#hapusIstriModal input[name=temp_mutasi_id]').val(id);
			
		});
		
		$("#nBtnHapusIstri").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmHapusIstri').serialize();
			
			$('#hapusIstriModal #msg').text('Deleting Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/hapusIstri",
				data: data,
				success: function(){					
					$('#hapusIstriModal #msg').text('Berhasil menghapus data....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshIstri();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
		
		function refreshAnakJd(){						
			$.ajax({   
			    type: 'GET',   
			    url: '<?php echo site_url()?>/verifikator/getAnakJdAll',   
			    data:  $('#anakFormJd').serialize(),
			    success: function(res) {
					$("#tb-anak").html(res);
				},
			});
		}
		
		function refreshAnak(){		
			$.ajax({   
				type: 'GET',   
				url: '<?php echo site_url()?>/verifikator/getAnakAll',
				data:  $('#anakForm').serialize(),
				success: function(res) {
					$("#tb-anak").html(res);
				},
			});
		}

		
		function refreshIstri(){						
			$.ajax({   
			    type: 'GET',   
			    url: '<?php echo site_url()?>/verifikator/getIstriAll',   
			    data:  $('#istriForm').serialize(),
			    success: function(res) {
					$("#tb-istri").html(res);
				},
			});
		}
	});	
   </script>
  </body>
</html>
