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
						  <h3 class="box-title">Verifikasi Usul Instansi</h3>
						</div><!-- /.box-header -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/verifikator/find">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							<div class="box-body">
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-2">Usul</label>
									<div class="col-md-10 col-sm-10 col-xs-10">
										<input type="radio" value="1" name="usul"  <?php echo  set_radio('usul', 1, TRUE); ?> />&nbsp;INSTANSI DAERAH/PUSAT
										<?php if($this->session->userdata('session_bidang') == 2):?>
										<input type="radio" value="2" name="usul"  <?php echo  set_radio('usul', 2); ?>/>&nbsp;TASPEN									
										<?php endif;?>
									</div>	
									<span class="help-block text-red"><?php echo form_error('usul'); ?></span>
								</div> 	
								
								<div class="form-group">									   						 
									<div class="col-md-2 col-xs-4 col-sm-3">
										<select name="searchby" class="form-control">
											<option value="">--silahkan Pilih--</option>
											<option value="1" <?php echo  set_select('searchby',1); ?>>NIP</option>
											<option value="2" <?php echo  set_select('searchby',2); ?>>INSTANSI</option>
											<option value="3" <?php echo  set_select('searchby',3); ?>>NOMOR USUL</option>
											<option value="4" <?php echo  set_select('searchby',4); ?>>PELAYANAN</option>
										</select>
										<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
									</div>	
									<div class="col-md-9 col-xs-8 col-sm-9">									
										<input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>" />
										 <span class="help-block text-red"><?php echo form_error('search'); ?></span>
									</div>
									<div class="col-md-1">
										<button type="submit" class="btn btn-default" type="button"><i class="fa fa-search"> Cari!</i></button>
									</div>								                            
								</div>
							</div><!-- /.box-body -->
						</form>
					    <hr/>
						<?php if($show  == TRUE) :?>
						<div class="table-responsive">
							<table class="table table-striped">
							<thead>
								<tr>
									<th>NO USUL</th>
									<th>INSTANSI</th>
									<th>NIP</th>
									<th>GOL</th>
									<th>NAMA</th>
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
								    <?php 									
									switch($value->tahapan_id){
										case 4:
										    $n = $value->work_name;
										break;
										case 5:
										    $n = $value->work_name;
										break;
										case 6:
										    $n = $value->lock_name;
										break;
										case 7:
										    $n = $value->verif_name_satu;
										break;
										case 8:
										    $n = $value->lock_name;
										break;
										case 9:
										    $n = $value->verif_name_dua;
										break;
										case 10:
										    $n = $value->lock_name;
										break;
										case 11:
										    $n = $value->verif_name_tiga;
										break;
										case 12:
										    $n = $value->entry_proses_name;
										break;
										case 13:
										    $n = $value->entry_name;
										break;										
										default:
										   $n = "";
									}									
									?>
									<td><?php echo $value->agenda_nousul?></td>
									<td><?php echo $value->instansi?></td>
									<td><?php echo $value->nip?></td>
									<td><?php echo $value->golongan?></td>
									<td><?php echo $value->nama?></td>
									<td><?php echo $value->agenda_timestamp?></td>														
									<td><?php echo $value->layanan_nama?></td>
									<td><?php echo (!empty($value->main_upload_dokumen) ?  '<i data-tooltip="tooltip" data-toggle="modal" data-target="#berkasAdaModal" data-id="?id='.$this->myencrypt->encode($value->upload_dokumen).'" title="Ada Nota Usul" class="fa fa-check" style="color:green"></i>' : '<i data-tooltip="tooltip" title="Tidak Ada Nota Usul" class="fa fa-remove" style="color:red"></i>')?></td>		
									<td><?php echo $value->tahapan_nama.' '.$n?></td>
									<td><?php echo $value->nomi_status?></td>								
									<td>
									<?php echo ($value->nomi_locked == "1" ? '<a href="#" class="btn btn-warning btn-xs" data-tooltip="tooltip" data-toggle="modal" data-target="'.( $value->locked_by === $this->session->userdata('user_id') ? '#bukalockModal' : '#').'" title="Terkunci oleh '.$value->lock_name.'" data-id="'.$value->agenda_id.'" data-nip="'.$value->nip.'"><i class="fa fa-lock"></i></a>' : '')?>
									<?php if(!empty($value->main_upload_dokumen)){
										echo '<a href="#nVerify" class="btn btn-primary btn-xs" data-tooltip="tooltip" title="Verifikasi Berkas" id="?n='.$this->myencrypt->encode($value->nip).'&i='.$this->myencrypt->encode($value->agenda_id).'&p='.$this->myencrypt->encode($value->layanan_id).'&t='.$this->myencrypt->encode($value->tahapan_id).'"><i class="fa fa-file-o"></i></a>';							
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
							<input class="form-control" type="hidden" value="" name="agenda" />	
							<input class="form-control" type="hidden" value="" name="nip" />					
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
        
		$('#berkasAdaModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/verifikator/getKelengkapan/'+id, function(data){
				$('#berkasAdaModal').find('.modal-content').html(data);
			})			
	    });
		
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('a[href="#nVerify"]').on("click",function(e){
			var id= this.id;
			window.location.replace('<?php echo site_url()?>/verifikator/verifyGet/'+id);
		   
		});	
		
		$('#bukalockModal').on('show.bs.modal',function(e){
		    $('#bukalockModal #msg').text('Konfirmasi Buka Kunci Berkas')
			var nip   =  $(e.relatedTarget).attr('data-nip');
			var id    =  $(e.relatedTarget).attr('data-id');
			$('[name=agenda]').val(id);
			$('[name=nip]').val(nip);
		});
		
		$("#nBtnbuka").on("click",function(e){
			e.preventDefault();
		    $('#bukalockModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
					 
			var data = $('#nfrmbukaLock').serialize();
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikator/unlock",
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
