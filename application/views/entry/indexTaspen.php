<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />

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
	  
	     <!-- Content Header (Page header) -->
        <section class="content-header">          
           <section class="content-header">          
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="">Entry</li>
			<li class="active">Berkas</li>
          </ol>
        </section>
			
          </ol>
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Entry Berkas Persetujuan</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmEntry" class="form-horizontal" method="post" action="<?php echo site_url()?>/entry/getEntry" role="form">
						 <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						  <div class="box-body">
							<div class="form-group">
							  <label class="col-md-2">Instansi</label>
							  <div class="col-md-10">
							    <select name="instansi" class="form-control select2">
									<option value="">--</option>
									<option value="9" <?php echo  set_select('instansi',9); ?>>TASPEN</option>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>"  <?php echo  set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>									
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group">
							  <label class="col-md-2">Pelayanan</label>
							  <div class="col-md-10">
							    <select name="layanan" class="form-control">
								    <option value="">--</option>
									<?php if($layanan->num_rows() > 0):?>
									<?php foreach($layanan->result() as $value):?>
									<option value="<?php echo $value->layanan_id?>" <?php echo  set_select('layanan', $value->layanan_id); ?>><?php echo $value->layanan_nama?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('layanan'); ?></span>
							  </div>	
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Periode ACC</label>
								<div class="col-md-4 controls">
								  <div class="input-group">
									<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
									<input type="text"  style="" name="reportrange" id="reportrange" class="form-control" value="<?php echo date("d/m/Y", strtotime( "-1 month" )).' - '.date( "d/m/Y")?>"/>  
								  </div>
								  <span class="help-block text-red"><?php echo form_error('reportrange'); ?></span>
								</div>
                                
								<label class=" control-label col-md-2">Pemeriksa</label>									
								<div class="col-md-10">
									<select name="spesimen" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<?php if($spesimen->num_rows() > 0):?>
										<?php foreach($spesimen->result() as $value):?>
										<option value="<?php echo $value->user_id?>" <?php echo  set_select('spesimen', $value->user_id); ?> ><?php echo $value->first_name.' '.$value->last_name;?></option>
										<?php endforeach;?>
										<?php endif;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('spesimen'); ?></span>
								</div>										
																
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2">Filter</label>									
								<div class="col-md-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?> >NIP</option>
										<option value="2" <?php echo  set_select('searchby', '2'); ?> >NOMOR USUL</option>											
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>								
							</div>			
							<div class="form-group row">
							    <label class="control-label col-md-2">Status:</label>
								<div class="col-md-4">
								    <input type="radio" value="1" name="status"  <?php echo  set_radio('status', 1);?>  />&nbsp;Sudah Entry
									<input type="radio" value="2" name="status"  <?php echo  set_radio('status', 2);?> />&nbsp;Belum Entry
									<input type="radio" value="3" name="status"  <?php echo  set_radio('status', 3);?> <?php echo (!$this->input->post('status') ? "checked" : "")?>  />&nbsp;Semua
									<span class="help-block text-red"><?php echo form_error('status'); ?></span>
								</div>
								<label class="control-label col-md-2">Perintah:</label>
								<div class="col-md-4">
									<input type="radio" value="1" name="perintah"  <?php echo  set_radio('perintah', 1);?> <?php echo (!$this->input->post('perintah') ? "checked" : "")?> />&nbsp;Tampil
									<input type="radio" value="2" name="perintah"  <?php echo  set_radio('perintah', 2);?>/>&nbsp;Download									
									<span class="help-block text-red"><?php echo form_error('perintah'); ?></span>
								</div>	
							</div> 	
							
						   </div>
						    <div class="box-footer">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Cari</button>
						  </div>
						</form>
						<hr/>
						<?php if($show):?>
						<div class="table-responsive">
						<table id="tb-entry"  class="table table-striped table-condensed">
						<thead>
							<tr>
								<th style="width:125px;"></th>
								<th>NOMOR</th>
								<th>NIP</th>
								<th>NAMA PNS</th>	
								<th>NAMA</th>
								<th>PELAYANAN</th>                               						
								<th style="width:125px;">ACC DATE</th>
								<th>FILE</th>
								<th>PERSETUJUAN</th>							
							</tr>
						</thead>   
						<tbody>
							<?php if($usul->num_rows() > 0):?>
							<?php  foreach($usul->result() as $value):?>
							<tr>
								<td>
								<?php 
								    echo '<a href="#dPhoto" class="btn btn-info btn-xs" data-tooltip="tooltip"  title="Unduh Photo" id="?id='.$this->myencrypt->encode($value->usul_id).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-download"></i></a>';
									echo '&nbsp;<a href="#cetakSurat" class="btn btn-danger btn-xs cetak" data-tooltip="tooltip"  title="Cetak Surat Persetujuan" id="?a='.$this->myencrypt->encode($value->usul_id).'&n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_id).'"><i class="fa fa-print"></i></a>';
									echo '&nbsp;<button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModal"   data-usul="'.$this->myencrypt->encode($value->usul_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></button>';
									echo '&nbsp;<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Upload Persetujuan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'" ><i class="fa fa-upload"></i></button>';
								?>
								</td>
								<td><?php echo $value->nomor_usul;?></td>								
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama_pns?></td>	
								<td><?php echo $value->nama_janda_duda?></td>
								<td><?php echo $value->layanan_nama?></td>
								<td><?php echo $value->usul_verif_date.' Oleh :<b>'.$value->usul_verif_name.'</b>'?></span></td>
								<td>
								<?php
								if(!empty($value->upload_persetujuan))
								{
									$file = $value->file_persetujuan;
									
									echo '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
									<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?id='.$this->myencrypt->encode($value->usul_id).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
								}
								else
								{
									echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
									<i class="fa fa-file-o" style="color:red;"></i></span>';
								}								
								
                                ?>	
								</td>
								<td><?php echo $value->usul_no_persetujuan?><br/><?php echo $value->usul_tgl_persetujuan?></td>								
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
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" ><span id="msg"></span></h4>
				</div>	
				
				<div class="modal-body">
					<form id="nfrmPersetujuan">
					    <input class="form-control" type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>">
		                <input class="form-control" type="hidden" value="" name="nip" />
						<input class="form-control" type="hidden" value="" name="usul" />
						<input class="form-control" type="hidden" value="" name="layananId" />
						
						<div class="form-group row">
							<label class="control-label col-md-2 col-sm-2 col-xs-2">Nomor Surat</label>
							<div class="col-md-4 col-sm-4 col-xs-4">	
								<input class="form-control" type="text" value="" name="persetujuan" />	
                            </div> 
							<label class="control-label col-md-2 col-sm-2 col-xs-2">Tanggal</label>	
							<div class="col-md-4 col-sm-4 col-xs-4">	
								<div class='input-group date'>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' required name="tanggal" value="<?php echo date('d-m-Y')?>" class="form-control datetimepicker" />
																	
								</div>
							</div>	
						</div>	
						<div class="form-group row">
							<label class="control-label col-md-2 col-sm-2 col-xs-2">Pensiun Pokok</label>
							<div class="col-md-4 col-sm-4 col-xs-4">	
								<input class="form-control" type="text" value="" name="pensiun_pokok" />	
                            </div> 
							<label class="control-label col-md-2 col-sm-2 col-xs-2">Pensiun TMT</label>	
							<div class="col-md-4 col-sm-4 col-xs-4">	
								<div class='input-group date'>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input   pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text'  name="pensiun_tmt" value="<?php echo date('d-m-Y')?>" class="form-control datetimepicker" />
																	
								</div>
							</div>												
						</div>
						<div class="form-group row">
							<label class="control-label col-md-2 col-sm-2 col-xs-2">Tanggal Menikah</label>
							<div class="col-md-4 col-sm-4 col-xs-4">	
								<div class='input-group date'>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input   pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text'  name="tgl_menikah" value="<?php echo date('d-m-Y')?>" class="form-control datetimepicker" />
																	
								</div>
							</div>	 
							<label class="meninggal control-label col-md-2 col-sm-2 col-xs-2 ">Meninggal</label>	
							<div class="meninggal col-md-4 col-sm-4 col-xs-4">	
								<div class='input-group date'>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input   pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text'  name="tgl_meninggal" value="<?php echo date('d-m-Y')?>" class="form-control datetimepicker" />
																	
								</div>
							</div>												
						</div>
						<div class="form-group row">
						   <label class="control-label col-md-2 col-sm-2 col-xs-2">Gaji Pokok</label>
							<div class="col-md-4 col-sm-4 col-xs-4">	
								<input class="form-control" type="text" value="" name="gaji_pokok_terakhir" />	
                            </div> 
						    <label class="col-sm-2 col-md-2 col-xs-2">Kantor Taspen</label>
						    <div class="col-sm-4 col-md-4 col-xs-4">
							<select name="kantor" class="form-control">
								<option value="">--</option>
								<?php foreach($kantor->result() as $value):?>
								<option value="<?php echo $value->id_taspen?>"  <?php echo  set_select('kantor', $value->id_taspen); ?>><?php echo $value->nama_taspen?></option>
								<?php endforeach;?>									
							</select>
							<span class="help-block text-red"><?php echo form_error('kantor'); ?></span>
						  </div>	
						</div>
						
						<div class="form-group row">
							<label class=" control-label col-md-2 col-sm-2 col-xs-2">Spesimen</label>									
							<div class="col-md-10 col-sm-10 col-xs-10">
								<select name="spesimenTaspen" class="form-control">
									<option value="">--silahkan Pilih--</option>
									<?php if($spesimenTaspen->num_rows() > 0):?>
									<?php foreach($spesimenTaspen->result() as $value):?>
									<option value="<?php echo $value->nip?>"><?php echo $value->nama_spesimen?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('spesimen'); ?></span>
							</div>						
						</div>	
					</form>
				</div>
				<div class="modal-footer">
				   <button type="button" class="btn btn-default" id="nBtnPersetujuan">Simpan</button>
				</div>
		    </div>
		</div>
	</div>	
	
	
	
	<div id="uploadModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="fileUploadForm">
					    <input class="form-control" type="hidden" value="" name="usul_id" />
						<input class="form-control" type="hidden" value="" name="usul_nip" />
						<input class="form-control" type="hidden" value="" name="usul_layanan" />					
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        Select file : <input type='file' name='file' id='file' class='form-control' ><br>
                        <input type='button' class='btn btn-info' value='Upload' id='btn_upload'>
                    </form>
                </div>                
            </div>
        </div>
    </div>
	
	<div class="modal" id="showFile" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog  md-dialog modal-lg">
		  <div class="modal-content md-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >File Persetujuan Teknis</h4>
				</div>	
				<div class="modal-body md-body">
					<iframe  id="frame" width="100%" height="100%" frameborder="0" ></iframe>	
					
				</div>
		  </div>
		</div>
	</div>	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/daterange/daterangepicker.js"></script>

	<script>	
	$(document).ready(function () {
		
		// hide empty column
		var columns = $("#tb-entry > tbody > tr:first > td").length;
		for (var i = 0; i < columns; i++) {
			if ($("#tb-entry > tbody > tr > td:nth-child(" + i + ")").filter(function() {
			  return $(this).text() != '';
			}).length == 0) {
			  $("#tb-entry > tbody > tr > td:nth-child(" + i + "), #tb-entry > thead > tr > th:nth-child(" + i + ")").hide();
			}
		} 
		
		$('[data-tooltip="tooltip"]').tooltip();
		
	    $(".select2").select2({
			width: '100%'
		});			
		
		$('#reportrange').daterangepicker({
			   format: 'DD/MM/YYYY',
			   minDate: '01/04/2015',
			   startDate : moment().subtract(1, 'months'),
			   locale: 'id',
	    });	
		
		$('.datetimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		
		$('#skModal').on('show.bs.modal',function(e){
		    var usul=  $(e.relatedTarget).attr('data-usul'),
			    nip   =  $(e.relatedTarget).attr('data-nip');
			
			$('#skModal #msg').text('Input Realisasi Persetujuan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
			$("input[name=persetujuan]").val('');
			$("input[name=tanggal]").val('');
			$("input[name=pensiun_pokok]").val('');
			$("input[name=pensiun_tmt]").val('');
			$("input[name=tgl_meninggal]").val('');
			$("input[name=tgl_menikah]").val('');
			$("input[name=gaji_pokok_terakhir]").val('');
			$("[name=spesimenTaspen]").val('');
			$("input[name=usul]").val(usul);
			$("input[name=nip]").val(nip);
			
			$.ajax({
				type: "GET",
				url : "<?php echo site_url()?>/entry/simpanTahapanTaspen",
				data: {usul:usul,nip:nip},
				dataType:'json',
				success: function(r){
					$('#skModal input[name=persetujuan]').val(r.entry[0].usul_no_persetujuan);	
                    $('#skModal input[name=tanggal]').val(r.entry[0].tgl_persetujuan);	
					$('#skModal input[name=pensiun_pokok]').val(r.entry[0].pensiun_pokok);	
					$('#skModal input[name=pensiun_tmt]').val(r.entry[0].tmt_pensiun);
					$('#skModal [name=kantor]').val(r.entry[0].kantor_taspen);
					$('#skModal input[name=tgl_menikah]').val(r.entry[0].atgl_perkawinan);
					$('#skModal input[name=tgl_meninggal]').val(r.entry[0].tgl_meninggal);
					$('#skModal input[name=gaji_pokok_terakhir]').val(r.entry[0].gaji_pokok_terakhir);
					$('#skModal [name=spesimenTaspen]').val(r.entry[0].usul_spesimen);
					$('#skModal input[name=layananId]').val(r.entry[0].layanan_id);

					if(r.entry[0].layanan_id == 15)
					{	
						$('.meninggal').addClass( "hidden" ); 
					}
					else
					{
						$('.meninggal').removeClass( "hidden" );
					}		
				},
			});	
		});
		
		$("#nBtnPersetujuan").on("click",function(e){
			e.preventDefault();			
			var data = $('#nfrmPersetujuan').serialize();
					
			$('#skModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/entry/simpanTaspen",
				data: data,
				dataType:'json',
				success: function(e){
					$('#skModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshTable();		 
				}, 
				error : function(e){
					$('#skModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});	
		
		$('.table-responsive').on("click",'a[href="#dPhoto"]',function(e){
			var id= this.id;
		    document.location  = "<?php echo site_url()?>/entry/getPhotoTaspen/"+id;
		}); 
		
					
		//event delegate supaya aktif after ajax call		
		$('.table-responsive').on("click",'a[href="#cetakSurat"]',function(e){
			var id= this.id;
		    document.location  = "<?php echo site_url()?>/entry/cetakSuratTaspen/"+id;
		}); 
		
		$('#uploadModal').on('show.bs.modal',function(e){
			
			$('#uploadModal #msg').text('Upload File Persetujuan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
		   
			var nip   		=  $(e.relatedTarget).attr('data-nip'),
			    usul   	    =  $(e.relatedTarget).attr('data-usul'),
			    layanan   	=  $(e.relatedTarget).attr('data-layanan');		
			
			$("input[name=usul_nip]").val(nip);
			$("input[name=usul_id]").val(usul);
			$("input[name=usul_layanan]").val(layanan);
			
		});
		
		$('#btn_upload').click(function(){
			var form = $('#fileUploadForm')[0];
			// Create an FormData object 
			var data = new FormData(form);
			
			// AJAX request
			$.ajax({
				url: '<?php  echo site_url()?>/entry/uploadTaspen',
				type: 'post',
				data: data,
				contentType: false,
				processData: false,
				cache:false,
				success: function(e){                        
					$('#uploadModal #msg').text(e.pesan)
						 .removeClass( "text-blue")
						 .removeClass( "text-red")
						 .addClass( "text-green" );
					refreshTable();	 
				},
				error : function(e){
					$('#uploadModal #msg').text(e.responseJSON.error)
						 .removeClass( "text-blue")							 
						 .removeClass( "text-green")
						 .addClass( "text-red" ); 
				}	
            });
        });
		
		$('#showFile').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/entry/getInlineTaspen/'+id);			
	    });
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/entry/getEntryAllTaspen',   
			    data: $('form[name=frmEntry]').serialize(),
			    success: function(res) {
					$("#tb-entry").html(res);
				},
			});
		}
	});
	
	
</script>
	</body>
</html>
