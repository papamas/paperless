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
							  <label class="col-sm-2 col-md-2 col-xs-2">Instansi</label>
							  <div class="col-sm-10 col-md-10 col-xs-10">
							    <select name="instansi" class="form-control select2">
									<option value="">--</option>
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>"  <?php echo  set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group">
							  <label class="col-sm-2 col-md-2 col-xs-2">Pelayanan</label>
							  <div class="col-sm-10 col-md-10 col-xs-10">
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
							<div class="form-group row">
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Periode ACC</label>
								<div class="col-sm-4 col-md-4 col-xs-4 controls">
								  <div class="input-group">
									<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
									<input type="text"  style="" name="reportrange" id="reportrange" class="form-control" value="<?php echo date("d/m/Y", strtotime( "-1 month" )).' - '.date( "d/m/Y")?>"/>  
								  </div>
								  <span class="help-block text-red"><?php echo form_error('reportrange'); ?></span>
								</div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
									<label class=" control-label col-md-2 col-sm-2 col-xs-2">Spesimen</label>									
									<div class="col-md-10 col-sm-10 col-xs-10">
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
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">Filter</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?> >NIP</option>
										<option value="2" <?php echo  set_select('searchby', '2'); ?> >NOMOR USUL</option>											
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>								
							</div>			
							<div class="form-group row">
							    <label class="control-label col-md-2 col-sm-2 col-xs-2">Status:</label>
								<div class="col-md-4 col-sm-10 col-xs-10">
								    <input type="radio" value="1" name="status"  <?php echo  set_radio('status', 1);?>  />&nbsp;Sudah Entry
									<input type="radio" value="2" name="status"  <?php echo  set_radio('status', 2);?> />&nbsp;Belum Entry
									<input type="radio" value="3" name="status"  <?php echo  set_radio('status', 3);?> <?php echo (!$this->input->post('status') ? "checked" : "")?>  />&nbsp;Semua
									<span class="help-block text-red"><?php echo form_error('status'); ?></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Perintah:</label>
								<div class="col-md-4 col-sm-10 col-xs-10">
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
								<th style="width:100px;"></th>
								<th>NOMOR</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>								
								<th>PELAYANAN</th>                               						
								<th style="width:150px;">ACC DATE</th>
								<th style="width:55px;">FILE</th>
								<th>PERSETUJUAN</th>							
							</tr>
						</thead>   
						<tbody>
							<?php if($usul->num_rows() > 0):?>
							<?php  foreach($usul->result() as $value):?>
							<tr>
								<td>
								<?php 
								    $layanan = $value->layanan_id;
									if($layanan === "9" || $layanan === "10" || $layanan === "11")
									{  
										echo '&nbsp;<a href="#dPhoto" class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Unduh Photo" id="?id='.$this->myencrypt->encode($value->id_instansi).'&f='.$this->myencrypt->encode($value->orig_name).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-search"></i></a>';
									}
									
                                    if($layanan === "14")
									{  
										echo '<button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModalPG" data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></button>';
										echo '&nbsp;<a href="#cetakSurat" class="btn btn-danger btn-xs cetak" data-tooltip="tooltip"  title="Cetak Surat Peningkatan Pendidikan" id="?a='.$this->myencrypt->encode($value->agenda_id).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-print"></i></a>';
									}
									else
									{
										echo '&nbsp;<button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModal"   data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></button>';
								
									}

									echo '&nbsp;<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Upload Persetujuan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-agenda="'.$value->agenda_id.'" data-instansi="'.$value->agenda_ins.'" data-nip="'.$value->nip.'"><i class="fa fa-upload"></i></button>';
																			
																	
								?>
								</td>
								<td><?php echo $value->agenda_nousul;?></td>
								<td><?php echo $value->instansi?></td>
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama?></td>																					
								<td><?php echo $value->layanan_nama?></td>
								<td><?php echo $value->verify_date.' Oleh :<b>'.$value->verif_name.'</b>'?></span></td>
								<td>
								<?php
								if(!empty($value->upload_persetujuan))
								{
									switch($value->layanan_id){
										case 1:
											$name  = 'NPKP_';				
										break;
										case 2:
											$name  = 'NPKP_';				
										break;
										case 3:
											$name  = 'NPKP_';			
										break;			
										case 4:
											$name  = 'PERTEK_PENSIUN_';				
										break;
										case 6:
											$name  = 'PERTEK_PENSIUN_';				
										break;
										case 7:
											$name  = 'PERTEK_PENSIUN_';				
										break;
										case 8:
											$name  = 'PERTEK_PENSIUN_';				
										break;
										case 9:
											$name  = 'KARIS_';				
										break;
										case 10:
											$name  = 'KARSU_';				
										break;
										case 11:
											$name  = 'KARPEG_';				
										break;
										case 12:
											$name  = 'NPKP_';			
										break;
										case 13:
											$name  = 'SK_MUTASI';			
										break;
										case 14:
											$name  = 'SK_PG_';			
										break;
									}	
									
									$file = $name.$value->nip.'.pdf';
																										
									echo '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
									<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
								}
								else
								{
									echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
									<i class="fa fa-file-o" style="color:red;"></i></span>';
								}
								
								
								if(!empty($value->upload_sk))
								{
									switch($value->layanan_id){
										case 1:
											$name  = 'SK_KP_';				
										break;
										case 2:
											$name  = 'SK_KP_';				
										break;
										case 3:
											$name  = 'SK_KP_';			
										break;			
										case 4:
											$name  = 'SK_PENSIUN_';				
										break;
										case 6:
											$name  = 'SK_PENSIUN_';				
										break;
										case 7:
											$name  = 'SK_PENSIUN_';				
										break;
										case 8:
											$name  = 'SK_PENSIUN_';				
										break;
										case 9:
											$name  = 'KARIS_';				
										break;
										case 10:
											$name  = 'KARSU_';				
										break;
										case 11:
											$name  = 'KARPEG_';				
										break;
										case 12:
											$name  = 'SK_KP_';			
										break;
										case 13:
											$name  = 'SK_MUTASI';			
										break;
										case 14:
											$name  = 'SK_PG_';			
										break;
									}	
									
									$file = $name.$value->nip.'.pdf';
									
									echo '<span data-toggle="tooltip" data-original-title="Ada File Surat Keputusan">
									<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
								}
								else
								{
									echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Surat Keputusan">
									<i class="fa fa-file-o" style="color:red;"></i></span>';
								}
                                ?>	
								</td>
								<td><?php echo $value->nomi_persetujuan?><br/><?php echo $value->tgl?></td>								
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
	    <div class="modal-dialog modal-sm">
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" ><span id="msg"></span></h4>
				</div>	
				
				<div class="modal-body">
					<form id="nfrmPersetujuan">
					    <input class="form-control" type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>">
		                <input class="form-control" type="hidden" value="" name="nip" />
						<input class="form-control" type="hidden" value="" name="agenda" />
						
						<div class="form-group">
							<label class="control-label">Nomor</label>
							<input class="form-control" type="text" value="" name="persetujuan" />																	
						</div>	
						<div class="form-group">
							<label class="control-label">Tanggal</label>																
							<div class='input-group date'>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								<input  id='datetimepicker' pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' required name="tanggal" value="<?php echo date('d-m-Y')?>" class="form-control" />
																
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
	
	<div class="modal fade" id="skModalPG" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" ><span id="msg"></span></h4>
				</div>	
				
				<div class="modal-body">
					<form id="nfrmPersetujuanPG">
					    <input class="form-control" type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>">
		                <input class="form-control" type="hidden" value="" name="nip" />
						<input class="form-control" type="hidden" value="" name="agenda" />
						
						<div class="form-group row">
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Nomor</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
							    <input class="form-control" type="text" value="" name="persetujuan" />
							</div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tanggal</label>	
							<div class="col-md-4 col-sm-4 col-xs-4">									
								<div class='input-group date'>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input  id='dttimepicker' pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' required name="tanggal" value="<?php echo date('d-m-Y')?>" class="form-control" />
																	
								</div>								
							</div>									
						</div>							
						<div class="form-group row">
						    <label class="col-sm-2 col-md-2 col-xs-2 control-label">Ijazah</label>
							<div class="col-sm-10 col-md-10 col-xs-10">
								<select id="kode_ijazah" name="kode_ijazah" class="form-control">
									<option value="">--</option>
									<?php foreach($ijazah->result() as $value):?>
									<option value="<?php echo $value->kode_ijazah?>"><?php echo $value->nama_ijazah?></option>
									<?php endforeach;?>
								</select>	
                            </div>						   
						</div>
						<div class="form-group row">
						    <label class="col-sm-2 col-md-2 col-xs-2 control-label">No.Ijazah</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<input class="form-control" type="text" value="" name="nomor_ijazah" />	
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl.Ijazah</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date'>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input  id='dttimepicker2' pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' required name="tgl_ijazah" value="" class="form-control" />
																	
								</div>	
                            </div>
						</div>
						
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Nama Kampus</label>
							<div class="col-sm-6 col-md-6 col-xs-6">
								<input class="form-control" type="text" value="" name="kampus" />	
                            </div>
							<label class="col-sm-1 col-md-1 col-xs-1 control-label">Lokasi</label>
							<div class="col-sm-3 col-md-3 col-xs-3">
								<input class="form-control" type="text" value="" name="lokasi_kampus" />	
                            </div>
						</div>
					  
					    <div class="form-group row">
						    <label class="col-sm-2 col-md-2 col-xs-2 control-label">Program Studi</label>
							<div class="col-sm-6 col-md-6 col-xs-6">
								<input class="form-control" type="text" value="" name="prodi" />	
                            </div>
							<label class="col-sm-1 col-md-1 col-xs-1 control-label">Gelar</label>
							<div class="col-sm-3 col-md-3 col-xs-3">
								<input class="form-control" type="text" value="" name="nama_gelar" />	
                            </div>
						</div>
						
					</form>
				</div>
				<div class="modal-footer">
				   <button type="button" class="btn btn-default" id="nBtnPersetujuanPG">Simpan</button>
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
					    <input class="form-control" type="hidden" value="" name="agenda_ins" />
						<input class="form-control" type="hidden" value="" name="agenda_id" />
						<input class="form-control" type="hidden" value="" name="agenda_nip" />
						<input class="form-control" type="hidden" value="" name="agenda_layanan" />
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
		
		$('#datetimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#dttimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#dttimepicker2').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#skModal').on('show.bs.modal',function(e){
		    var agenda=  $(e.relatedTarget).attr('data-agenda');
			var nip   =  $(e.relatedTarget).attr('data-nip');
			
			$('#skModal #msg').text('Input Realisasi Persetujuan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
			$("input[name=persetujuan]").val('');
			$("input[name=tanggal]").val('');
			$("input[name=agenda]").val(agenda);
			$("input[name=nip]").val(nip);
			
			$.ajax({
				type: "GET",
				url : "<?php echo site_url()?>/entry/simpanTahapan",
				data: {agenda:agenda,nip:nip},
				dataType:'json',
				success: function(r){
					$('#skModal input[name=persetujuan]').val(r.entry[0].nomi_persetujuan);	
                    $('#skModal input[name=tanggal]').val(r.entry[0].date_format);					
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
				url : "<?php echo site_url()?>/entry/simpan",
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
		
		$('#skModalPG').on('show.bs.modal',function(e){
		    var agenda=  $(e.relatedTarget).attr('data-agenda');
			var nip   =  $(e.relatedTarget).attr('data-nip');
			
			$('#skModalPG #msg').text('Input Realisasi Persetujuan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
			$("input[name=persetujuan]").val('');
			$("input[name=tanggal]").val('');
			$("input[name=agenda]").val(agenda);
			$("input[name=nip]").val(nip);
			
			$.ajax({
				type: "GET",
				url : "<?php echo site_url()?>/entry/simpanTahapan",
				data: {agenda:agenda,nip:nip},
				dataType:'json',
				success: function(r){
					$('#skModalPG input[name=persetujuan]').val(r.entry[0].nomi_persetujuan);	
                    $('#skModalPG input[name=tanggal]').val(r.entry[0].date_format);	
                    $('#skModalPG [name=kode_ijazah]').val(r.entry[0].kode_ijazah);					
                    $('#skModalPG input[name=nomor_ijazah]').val(r.entry[0].nomor_ijazah);	
                    $('#skModalPG input[name=tgl_ijazah]').val(r.entry[0].format_tgl_ijazah);
					$('#skModalPG input[name=kampus]').val(r.entry[0].kampus);	
					$('#skModalPG input[name=lokasi_kampus]').val(r.entry[0].lokasi_kampus);	
					$('#skModalPG input[name=prodi]').val(r.entry[0].prodi);
					$('#skModalPG input[name=nama_gelar]').val(r.entry[0].nama_gelar);	
				},
			});	
		});
		
		
		$("#nBtnPersetujuanPG").on("click",function(e){
			e.preventDefault();			
			var data = $('#nfrmPersetujuanPG').serialize();
					
			$('#skModalPG #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/entry/simpanPG",
				data: data,
				dataType:'json',
				success: function(e){
					$('#skModalPG #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshTable();		 
				}, 
				error : function(e){
					$('#skModalPG #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('a[href="#dPhoto"]').click(function(){
			var id= this.id;
		    document.location = "<?php echo site_url()?>/photo/getPhoto/"+id;
		}); 
				
		//event delegate supaya aktif after ajax call		
		$('.table-responsive').on("click",'a[href="#cetakSurat"]',function(e){
			var id= this.id;
		    document.location  = "<?php echo site_url()?>/entry/cetakSurat/"+id;
		}); 
		
		$('#uploadModal').on('show.bs.modal',function(e){
			
			$('#uploadModal #msg').text('Upload File Persetujuan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
		   
			var nip   		=  $(e.relatedTarget).attr('data-nip');
			var instansi    =  $(e.relatedTarget).attr('data-instansi');
			var agenda   	=  $(e.relatedTarget).attr('data-agenda');
			var layanan   	=  $(e.relatedTarget).attr('data-layanan');
			
			$("input[name=agenda_nip]").val(nip);
			$("input[name=agenda_ins]").val(instansi);
			$("input[name=agenda_id]").val(agenda);
			$("input[name=agenda_layanan]").val(layanan);
		});
		
		$('#btn_upload').click(function(){
			var form = $('#fileUploadForm')[0];
			// Create an FormData object 
			var data = new FormData(form);
			
			// AJAX request
			$.ajax({
				url: '<?php  echo site_url()?>/entry/upload',
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
			iframe.attr('src', '<?php echo site_url()?>'+'/entry/getInline/'+id);			
	    });
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/entry/getEntryAll',   
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
