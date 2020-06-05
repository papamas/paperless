<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
  </head> 
	
	<style>
  </style>
  </head>
  <body class="hold- skin-yellow sidebar-collapse">
  <div class="wrapper">	
	 <header class="main-header">
        <!-- Logo -->
        <?php echo $this->load->view('vlogo');?>
        <!-- navbar header-->
		<?php echo $this->load->view('vnavbar-header');?>
        <!-- end navbar header -->
       </header>
       <!-- Left side column -->
        <?php echo $this->load->view('vleft-side');?>
       <!-- End Left side column -->
	
	<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
	    <!-- Main content -->
        <section class="content ">
		   	<div class="row">
			    <div class="col-md-12 nopadding">
				   <div class="box box-default">
						<div class="box-header with-border">
						  <h3 class="box-title">Verifikasi Usul TASPEN</h3>
						</div><!-- /.box-header -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/verifikator/find">
						 <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						    <div class="box-body">
								<div class="form-group">									
									<label class="control-label col-md-2">Usul</label>
									<div class="col-md-4">
										<input type="radio" value="1" name="usul"  <?php echo  set_radio('usul', 1, TRUE); ?> />&nbsp;INSTANSI DAERAH/PUSAT
										<?php if($this->session->userdata('session_bidang') == 2):?>
										<input type="radio" value="2" name="usul"  <?php echo  set_radio('usul', 2); ?>/>&nbsp;TASPEN									
										<?php endif;?>
										<span class="help-block text-red"><?php echo form_error('usul'); ?></span>
									</div>
									
									<label class="control-label col-md-3">Tampilkan hanya yang belum diverifikasi oleh:</label>
									<div class="col-md-3">
										<select name="level" class="form-control">
										    <option value="">--silahkan Pilih--</option>
											<option value="1" <?php echo  set_select('level',1); ?>>Level 1</option>
											<option value="2" <?php echo  set_select('level',2); ?>>Level 2</option>
											<option value="3" <?php echo  set_select('level',3); ?>>Level 3</option>
										</select>
									</div>							
								</div> 	
								
								<div class="form-group">
                                    <label class="control-label col-md-2 visible-xs">Filter</label>								
									<div class="col-md-2">
										<select name="searchby" class="form-control">
											<option value="">--silahkan Pilih--</option>
											<option value="1" <?php echo  set_select('searchby',1); ?>>NIP</option>
											<option value="2" <?php echo  set_select('searchby',2); ?>>INSTANSI</option>
											<option value="3" <?php echo  set_select('searchby',3); ?>>NOMOR USUL</option>
											<option value="4" <?php echo  set_select('searchby',4); ?>>PELAYANAN</option>
										</select>
										<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
									</div>	
									<div class="col-md-9">									
										<input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
										<span class="help-block text-red"><?php echo form_error('search'); ?></span>
									</div>
									<div class="col-md-1">
										<button type="submit" class="btn btn-primary" type="button"><i class="fa fa-search" > Cari!</i></button>
									</div>
								</div>	
						    </div><!-- /.box-body -->                        
						</form>
					    <hr/>
						<?php if($show  == TRUE) :?>
						<div class="table-responsive">
							<table id="tb-verifikasi" class="table table-striped">
							<thead>
								<tr>
									<th>NO USUL</th>
									<th>NIP</th>
									<th>NAMA PNS</th>
									<th>NAMA JANDA/DUDA</th>
									<th>TANGGAL</th>
									<th style="width:100px;">PELAYANAN</th>
									<th>BERKAS</th>	
									<th style="width:50px;">TAHAP</th>									
									<th>STATUS</th>
									<th>PERINTAH</th>								
								</tr>
							</thead>   
							<tbody>
								<?php if($usul->num_rows() > 0 ):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
								    <td><?php echo $value->nomor_usul?></td>
									<td><?php echo $value->nip?></td>
									<td><?php echo $value->nama_pns?></td>
									<td><?php echo $value->nama_janda_duda?></td>
									<td><?php echo $value->kirim_bkn_date?></td>														
									<td><?php echo $value->layanan_nama?></td>
									<td><span style="color:white">x</span><?php echo (!empty($value->main_upload_dokumen) ?  '<i data-tooltip="tooltip" data-toggle="modal" data-target="#berkasAdaModal" data-id="?id='.$this->myencrypt->encode($value->upload_nama_dokumen).'" title="Ada Nota Usul" class="fa fa-check" style="color:green"></i>' : '<i data-tooltip="tooltip" title="Tidak Ada Nota Usul" class="fa fa-remove" style="color:red"></i>')?></td>		
									<td><?php echo $value->tahapan_nama?></td>
									<td><?php echo $value->usul_status?></td>								
									<td>
									<?php echo ($value->usul_locked == "1" ? '<a href="#" class="btn btn-warning btn-xs" data-tooltip="tooltip" data-toggle="modal" data-target="'.( $value->usul_lock_by === $this->session->userdata('user_id') ? '#bukalockModal' : '#').'" title="Terkunci oleh '.$value->usul_lock_name.'" data-id="'.$value->usul_id.'" data-nip="'.$value->nip.'"><i class="fa fa-lock"></i></a>' : '')?>
									<?php if(!empty($value->main_upload_dokumen)){
										echo '<a href="#nVerifyTaspen" class="btn btn-primary btn-xs" data-tooltip="tooltip" title="Verifikasi Berkas" id="?n='.$this->myencrypt->encode($value->nip).'&i='.$this->myencrypt->encode($value->usul_id).'&p='.$this->myencrypt->encode($value->layanan_id).'&t='.$this->myencrypt->encode($value->usul_tahapan_id).'"><i class="fa fa-file-o"></i></a>';							
									}
									?>
									</td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
														
							</tbody>
							</table>
						</div>	
						<?php endif;?>						
					</div>
				</div>					
			</div><!-- /.row -->
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<!-- Modal -->
	<div class="modal fade" id="berkasAdaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="">Infomasi Kelengkapan Berkas</h4>
				</div>
				<div class="modal-body">
				
				</div>				
			</div>
		</div>	
    </div>
	
	<div class="modal fade" id="bukalockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" ><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmbukaLock">
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
						<div class="form-group">
							<p>Anda Yakin akan membuka berkas ini? </p>
							<input class="form-control" type="hidden" value="" name="usul_id" />	
							<input class="form-control" type="hidden" value="" name="usul_nip" />					
						</div>
				    </form>	
				</div>	
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id="nBtnbuka">OK</button>
				</div>	
			</div>
		</div>	
    </div>
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/pdfo/pdfobject.js"></script>
	<script>	
	$(document).ready(function () {
		
		// hide empty column
		var columns = $("#tb-verifikasi > tbody > tr:first > td").length;
		for (var i = 0; i < columns; i++) {
			if ($("#tb-verifikasi > tbody > tr > td:nth-child(" + i + ")").filter(function() {
			  return $(this).text() != '';
			}).length == 0) {
			  $("#tb-verifikasi > tbody > tr > td:nth-child(" + i + "), #tb-verifikasi > thead > tr > th:nth-child(" + i + ")").hide();
			}
		} 
        
		$('#berkasAdaModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/verifikator/getKelengkapan/'+id, function(data){
				$('#berkasAdaModal').find('.modal-content').html(data);
			})			
	    });
		
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('a[href="#nVerifyTaspen"]').on("click",function(e){
			var id= this.id;
			window.location.replace('<?php echo site_url()?>/verifikator/verifyGetTaspen/'+id);
		   
		});	
		
		$('#bukalockModal').on('show.bs.modal',function(e){
		    $('#bukalockModal #msg').text('Konfirmasi Buka Kunci Berkas')
			var nip   =  $(e.relatedTarget).attr('data-nip');
			var id    =  $(e.relatedTarget).attr('data-id');
			$('[name=usul_id]').val(id);
			$('[name=usul_nip]').val(nip);
		});
		
		$("#nBtnbuka").on("click",function(e){
			e.preventDefault();
		    $('#bukalockModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
					 
			var data = $('#nfrmbukaLock').serialize();
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/unlockTaspen",
				data: data,
				success: function(){
					$('#bukalockModal #msg').text('Updated Succesfully.....')
                             .removeClass( "text-blue")
				             .addClass( "text-green" ); 					
				}, 
				error : function(r) {
				    
					 $('#bukalockModal #msg').text(r.responseJSON.error)
                     .removeClass( "text-green")
					 .removeClass( "text-blue")
				     .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		
	});	
   </script>
  </body>
</html>
