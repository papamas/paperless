<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>	  
  </head> 	
	
	<style>
    
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
				<li class="">Instansi</li>
				<li class="active">Daftar Photo</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Photo</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmDaftar" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/photo/getDaftar">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2">Instansi</label>
							  <div class="col-md-10">
							    <select name="instansi" class="form-control select2">									
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo ($this->session->userdata('session_instansi') == $value->INS_KODINS ? 'selected="selected"' : '')?>><?php echo $value->INS_NAMINS?></option>
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
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>
								<th>LAYANAN</th>
								<th>UPLOAD DATE</th>													
							</tr>
						</thead>   
						<tbody>
							<?php if($daftar->num_rows() > 0):?>
							<?php  foreach($daftar->result() as $value):?>							
							<tr>
								<td><button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat Photo" data-toggle="modal" data-target="#photoModal" data-id="?s=<?php echo $this->myencrypt->encode($value->file_size)?>&id=<?php echo $this->myencrypt->encode($value->id_instansi)?>&f=<?php echo $this->myencrypt->encode($value->orig_name)?>&n=<?php echo $this->myencrypt->encode($value->nip)?>"><i class="fa fa-search"></i></button>&nbsp;
								<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Delete Photo" data-toggle="modal" data-target="#dphotoModal" data-instansi="<?php echo $this->myencrypt->encode($value->id_instansi)?>" data-file="<?php echo $this->myencrypt->encode($value->orig_name)?>" data-path="<?php echo $this->myencrypt->encode($value->file_path)?>"><i class="fa fa-remove"></i></button></td>
								<td><?php echo $value->instansi?></td>
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama?></td>
								<td><?php echo $value->layanan_nama?></td>
								<td><?php echo $value->created_date?></td>						
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

        <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-hidden="true">
		   <div class="modal-dialog modal-sm" role="document">
			  <div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >File Photo</h4>
				  </div>	
				  <div class="modal-body">
					<img id="frame"  />	
				  </div>
			  </div>
			</div>
		</div>	
	
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->

    <div class="modal fade" id="dphotoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
				    
					<form id="nfrmdphoto">
					    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
						<p>Anda Yakin akan menghapus photo ini ?</p>					   	
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
	<script>	
	$(document).ready(function () {
	    
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('#photoModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/photo/getInline/'+id);			
	    });
		
		$('#dphotoModal').on('show.bs.modal',function(e){
		     $('#dphotoModal #msg').text('Konfirmasi Hapus Photo')
			.removeClass( "text-green")
			.removeClass( "text-danger")
		    .removeClass( "text-blue" ); 
			
			var instansi		=  $(e.relatedTarget).attr('data-instansi');
			var file 		    =  $(e.relatedTarget).attr('data-file');
			var path 		    =  $(e.relatedTarget).attr('data-path');
			
			$('#dphotoModal input[name=instansi]').val(instansi);
			$('#dphotoModal input[name=file]').val(file);
			$('#dphotoModal input[name=path]').val(path);
		});
		
		$("#nBtnHapus").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmdphoto').serialize();
			
			$('#dphotoModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/photo/hapus",
				data: data,
				success: function(r){					
					$('#dphotoModal #msg').text(r.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();				
			    }, // akhir fungsi sukses
				error : function(r) {
					$('#dphotoModal #msg').text(r.responseJSON.pesan)
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
			    url: '<?php echo site_url()?>/photo/getDaftarAll',   
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
