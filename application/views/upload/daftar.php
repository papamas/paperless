<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">

  </head> 	
	
	<style>
    /*Bootstrap modal size iframe*/
	@media (max-width: 1280px){
		.md-dialog  {
			height:630px;
			width:800px;
		}
		.md-body {
			height: 500px;	
		}
	}
	@media screen and (min-width:1281px) and (max-width:1600px){
		.md-dialog  {
			height:700px;
			width:1000px;
		}
		.md-body {
			height: 550px;	
		}
	}
	@media screen and (min-width:1601px) and (max-width:1920px){
		.md-dialog  {
			height:830px;
			width:1200px;
		}
		.md-body {
			height: 700px;	
		}
	}

	/*Vertically centering Bootstrap modal window*/
	.vertical-alignment-helper {
		display:table;
		height: 100%;
		width: 100%;
		pointer-events:none; /* This makes sure that we can still click outside of the modal to close it */
	}
	.vertical-align-center {
		/* To center vertically */
		display: table-cell;
		vertical-align: middle;
		pointer-events:none;
	}
	.md-content {
		/* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
		width:inherit;
		height:inherit;
		/* To center horizontally */
		margin: 0 auto;
		pointer-events: all;
	}
	
    </style>
  </head>
  <body class="hold-transition skin-yellow">
  <div class="wrapper">	
	 <header class="main-header">
        <!-- Logo -->
        <?php  $this->load->view('vlogo');?>
        <!-- navbar header-->
		<?php  $this->load->view('vnavbar-header');?>
        <!-- end navbar header -->
       </header>
       <!-- Left side column -->
        <?php  $this->load->view('vleft-side');?>
       <!-- End Left side column -->
	
	<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
	  
	     <!-- Content Header (Page header) -->
        <section class="content-header">          
           <section class="content-header">          
			  <ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
				<li class="">DMS</li>
				<li class="active">Daftar Dokumen</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Dokumen</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmDaftar" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/upload/getDaftar">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2">Instansi</label>
							  <div class="col-md-10">
							    <select name="instansi" class="form-control select2">
									<option value="">--</option>
									<?php if($this->session->userdata('session_instansi') == '4011'):?>
									<option value="9" <?php echo set_select('instansi', '9')?>>TASPEN</option>
									<?php endif;?>
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo set_select('instansi', $value->INS_KODINS)?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2">Filter</label>									
								<div class="col-md-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?> >NIP</option>	
										<option value="2" <?php echo  set_select('searchby', '2'); ?> >JENIS SK</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>								
							</div>
							<div class="form-group row">
							  	<label class="control-label col-md-2">Perintah</label>
								<div class="col-md-10">
									<input type="radio" required value="1" name="perintah"  checked />&nbsp;Tampil
									<input type="radio" required value="2" name="perintah"  />&nbsp;Download									
								</div>	
							</div> 	
							
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Cari</button>
							  </div>
							
						   </div>						   
						</form>
						<hr/>
						<?php if($show):?>
						<div class="table-responsive">
						<table id="tb-daftar" class="table table-striped">
						<thead>
							<tr>
								<th></th>
								<th>SK</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>
								<th>UPLOAD</th>
								<th>UPDATE</th>	
                                <th>BY</th>								
							</tr>
						</thead>   
						<tbody>
							<?php if($daftar->num_rows() > 0):?>
							<?php  foreach($daftar->result() as $value):?>
							<?php 
							$jenis_sk     = $value->nama_dokumen;
														
							if($jenis_sk != "IJAZAH" && $jenis_sk != "TRANSKRIP" && 
							$jenis_sk != "IBEL" && $jenis_sk != "MOU") 
							{
								switch($value->minor_dok){
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
										$n = $value->minor_dok;									
																				
								}	
							}
							else
							{
								
								switch($value->minor_dok){
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
									case 05:
										$n = "Sekolah Dasar";
									break;									
									default:
										$n = $value->minor_dok;									
																				
								}								
									
							}
							
							?>
							<tr>
								<td><button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat SK" data-toggle="modal" data-target="#skModal" data-id="?id=<?php echo $this->myencrypt->encode($value->id_instansi)?>&f=<?php echo $this->myencrypt->encode($value->orig_name)?>"><i class="fa fa-search"></i></button>&nbsp;
								<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Delete SK" data-toggle="modal" data-target="#dskModal" data-instansi="<?php echo $this->myencrypt->encode($value->id_instansi)?>" data-file="<?php echo $this->myencrypt->encode($value->orig_name)?>" data-path="<?php echo $this->myencrypt->encode($value->file_path)?>"><i class="fa fa-remove"></i></button></td> 
								<td><?php echo $value->nama_dokumen?> <?php echo $n?></td>
								<td><?php echo $value->instansi?></td>
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama?></td>
								<td><?php echo $value->created_date?></td>	
								<td><?php echo $value->update_date?></td>	
								<td><?php echo $value->name?></td>
							</tr>
							<?php endforeach;?>
							<?php endif;?>
													
						</tbody>
						</table>
						</div>
						<?php endif;?>
						
					</div>
                </div>
            </div> 	
			
        </section><!-- /.content -->	

        <div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-hidden="true">
		   <div class="modal-dialog modal-lg md-dialog">
			  <div class="modal-content md-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal">&times;</button>
					    <h4 class="modal-title" >File Dokumen Instansi</h4>
				    </div>	
				    <div class="modal-body md-body">
				        <div class="embed-responsive z-depth-1-half" style="height:100%">
							<iframe   id="frame" width="100%" height="100%" frameborder="0" ></iframe>	
						</div>
					</div>
			  </div>
			</div>
		</div>	
	
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->

	<div class="modal fade" id="dskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
				    
					<form id="nfrmdsk">
					    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
						<p>Anda Yakin akan menghapus dokumen SK ini ?</p>					   	
                        <input type="hidden" name="instansi"/>	
					    <input type="hidden" name="file"/>		
						 <input type="hidden" name="path"/>			
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapus">OK Hapus !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>

	<script>	
	$(document).ready(function () {
	    
		$(".select2").select2({
			placeholder:'--silahkan Pilih--',
			width: '100%'
		});	
		
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('#skModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/upload/getInline/'+id);			
	    });
		
		
		
		$('#dskModal').on('show.bs.modal',function(e){
		     $('#dskModal #msg').text('Konfirmasi Hapus SK')
			.removeClass( "text-green")
			.removeClass( "text-danger")
		    .removeClass( "text-blue" ); 
			
			var instansi		=  $(e.relatedTarget).attr('data-instansi');
			var file 		    =  $(e.relatedTarget).attr('data-file');
			var path 		    =  $(e.relatedTarget).attr('data-path');
			
			$('#dskModal input[name=instansi]').val(instansi);
			$('#dskModal input[name=file]').val(file);
			$('#dskModal input[name=path]').val(path);
		});
		
		$("#nBtnHapus").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmdsk').serialize();
			
			$('#dskModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/upload/hapus",
				data: data,
				success: function(r){					
					$('#dskModal #msg').text(r.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();				
			    }, // akhir fungsi sukses
				error : function(r) {
					$('#dskModal #msg').text(r.responseJSON.pesan)
						.removeClass( "text-blue")
						.removeClass( "text-green")
						.addClass( "text-danger" );
				}			
		    });
			return false;
		});
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/upload/getDaftarAll',   
			    data: $('form[name=frmDaftar]').serialize(),
			    success: function(res) {
					$("#tb-daftar").html(res);
					
				},
			});
		}
	});	
   </script>
	</body>
</html>
