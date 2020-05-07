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
							    foreach($tabs->result() as $value){
									echo '<li class=""><a href="#'.$this->myencrypt->encode($value->raw_name).'" data-toggle="tab" data-tooltip="tooltip" title="'.$value->keterangan.'" >'.$value->nama_dokumen.'</a></li>';
																	 
                                }								
								echo '</ul>';
							}
											
							?>

                            <div class="tab-content">
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
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>	
	<script>	
	$(document).ready(function () {
        
		$('[data-tooltip="tooltip"]').tooltip();
		
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
	});	
   </script>
  </body>
</html>
