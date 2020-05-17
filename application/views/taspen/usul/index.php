<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">

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
  <body class="hold-transition skin-yellow sidebar-collapse">
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
				<li class="">Taspen</li>
				<li class="active">Buat Usul</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Usul Taspen Janda/Duda/Yatim</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/taspen/saveUsul" accept-charset="utf-8" enctype="multipart/form-data">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							<input type="hidden" name="usul_id" value="<?php echo set_value('usul_id'); ?>"/>
							<div class="form-group row">	
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Nomor Usul</label>
							    <div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nomor_usul" class="form-control" placeholder="Nomor Usul" value="<?php echo set_value('nomor_usul'); ?>">						
									<span class="help-block text-red"><?php echo form_error('nomor_usul');?></span>
								</div>
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tanggal</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_usul" value="<?php echo (set_value('tgl_usul') ? set_value('tgl_usul') : date('d-m-Y'))?>" class="form-control datetimepicker" />									
									</div>			
                                    <span class="help-block text-red"><?php echo form_error('tgl_usul');?></span>									
								</div>	
							</div>
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">Pelayanan</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="layanan_id" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<?php foreach($layanan->result() as $value):?>
										<option value="<?php echo $value->layanan_id?>" <?php echo  set_select('layanan_id', $value->layanan_id); ?>><?php echo $value->layanan_nama?></option>
										<?php endforeach?>
									</select>
									<span class="help-block text-red"><?php echo form_error('layanan_id'); ?></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Surat Pengantar</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="file" name="file" class="form-control" />
									<span class="help-block text-red"><?php echo (!empty($error) ? $error : '') ?></span>

								</div>
							</div> 	
							
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">Nama <span id="tlabel"></span></label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nama_janda_duda" class="form-control" placeholder="Masukan Nama" value="<?php echo set_value('nama_janda_duda'); ?>">
									<span class="help-block text-red"><?php echo form_error('nama_janda_duda'); ?></span>
								</div>
								
								<label class=" control-label col-md-2 col-sm-2 col-xs-2">Nama PNS</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nama_pns" class="form-control" placeholder="Masukan Nama PNS" value="<?php echo set_value('nama_pns'); ?>">
									<span class="help-block text-red"><?php echo form_error('nama_pns'); ?></span>
								</div>
							</div>					
							
							<div class="form-group row">
								<label class=" control-label col-md-2 col-sm-2 col-xs-2">NOPEN / No. Dosir</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nopen" class="form-control" placeholder="Masukan NOPEN / No. Dosir" value="<?php echo set_value('nopen'); ?>">
									<span class="help-block text-red"><?php echo form_error('nopen'); ?></span>
								</div>
								
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">NIP / NRP / NVP</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="nip" class="form-control select2" >
										<option value="">--silahkan Pilih--</option>
										<?php foreach($upload->result() as $value):?>
										<option value="<?php echo $value->nip?>" <?php echo  set_select('nip', $value->nip); ?>><?php echo $value->nip?></option>
										<?php endforeach?>
									</select>
									<span class="help-block text-red"><?php echo form_error('nip'); ?></span>
								</div>
							</div>
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">Pangkat/Gol.Ruang</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="golongan" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<?php foreach($golongan->result() as $value):?>
										<option value="<?php echo $value->GOL_KODGOL?>" <?php echo  set_select('golongan', $value->GOL_KODGOL); ?>><?php echo $value->GOL_PKTNAM.' - '.$value->GOL_GOLNAM?></option>
										<?php endforeach?>
									</select>
									<span class="help-block text-red"><?php echo form_error('golongan'); ?></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Jabatan</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="jabatan" class="form-control" placeholder="Masukan Jabatan Terakhir" value="<?php echo set_value('jabatan'); ?>">
									<span class="help-block text-red"><?php echo form_error('jabatan'); ?></span>

								</div>
							</div>

							<div class="form-group row">
							  	<label class="control-label col-md-2 col-sm-2 col-xs-2">Unit Kerja Terkahir</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<input type="text" name="unit_kerja" class="form-control" placeholder="Masukan Unit Kerja Terakhir" value="<?php echo set_value('unit_kerja'); ?>">
									<span class="help-block text-red"><?php echo form_error('unit_kerja'); ?></span>
								</div>
								
							</div>
							
							<div class="form-group row">	
                                <label class="col-sm-2 col-md-2 col-xs-2 control-label">Tanggal Perkawinan</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_perkawinan" value="<?php echo set_value('tgl_perkawinan');?>" class="form-control datetimepicker" />
									</div>	
                                    <span class="help-block text-red"><?php echo form_error('tgl_perkawinan');?></span>								
									
								</div>							
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Meninggal Dunia</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="meninggal_dunia" value="<?php echo set_value('meninggal_dunia');?>" class="form-control datetimepicker" />
									</div>
									<span class="help-block text-red"><?php echo form_error('meninggal_dunia');?></span>								

								</div>
								
							</div>
							
							<div class="form-group row">									
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Gaji Pokok Terakhir</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<input type="number" name="gaji_pokok_terakhir" class="form-control" placeholder="Masukan Gaji Pokok Terakhir" value="<?php echo set_value('gaji_pokok_terakhir'); ?>">
									<span class="help-block text-red"><?php echo form_error('gaji_pokok_terakhir'); ?></span>						
								</div>
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Pensiun Pokok Terakhir</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<input type="number" name="pensiun_pokok_terakhir" class="form-control" placeholder="Masukan Pensiun Pokok Terakhir" value="<?php echo set_value('pensiun_pokok_terakhir'); ?>">
									<span class="help-block text-red"><?php echo form_error('pensiun_pokok_terakhir'); ?></span>						
								</div>
							</div>
							
							<div class="form-group row">
							  	<label class="control-label col-md-2 col-sm-2 col-xs-2">Alamat Ybs</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<input type="text" name="alamat" class="form-control" placeholder="Masukan Alamat Ybs" value="<?php echo set_value('alamat'); ?>">
									<span class="help-block text-red"><?php echo form_error('alamat'); ?></span>
								</div>								
							</div>
							
							<div class="box-footer">
								<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i>&nbsp;Simpan Usul</button>
							</div>
							
						   </div>						   
						</form>
						
						
						<div class="box-footer">
							<form class="navbar-form" name="frmUsul" method="POST" action="<?php echo site_url()?>/taspen/getUsul">
							   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
								<div class="form-group" style="display:inline;">
								  <div class="input-group" style="display:table;">
									<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
									<input class="form-control"  name="find" placeholder="Masukan NIP atau Nomor Usul" type="text" value="<?php echo set_value('find'); ?>">
								  </div>
								</div>						
							 </form>
						</div>
						
						<div class="table-responsive">
							<table id="tb-usul"  class="table table-striped table-condensed">
							<thead>
								<tr>
									<th style="width:95px;"></th>
									<th>NOMOR</th>
									<th>TGL USUL</th>
									<th>NIP</th>
									<th>NAMA PNS</th>
									<th>NAMA</th>								
									<th>PELAYANAN</th>
									<th>FILE</th>
									<th>SYSDATE</th>							
								</tr>
							</thead>   
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
									<td>
										<?php 
										echo'<a href="#edit" class=" btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Usul" data-nomor="'.$value->nomor_usul.'" data-tgl="'.$value->tgl.'" data-layanan="'.$value->layanan_id.'" data-nama="'.$value->nama_pns.'" data-jd="'.$value->nama_janda_duda.'" data-nopen="'.$value->nopen.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'" data-golongan="'.$value->golongan.'" data-jabatan="'.$value->jabatan.'" data-unit="'.$value->unit_kerja.'" data-perkawinan="'.$value->perkawinan.'" data-meninggal="'.$value->meninggal.'" data-gapok="'.$value->gaji_pokok_terakhir.'" data-penpok="'.$value->pensiun_pokok_terakhir.'" data-alamat="'.$value->alamat.'"><i class="fa fa-edit"></i></a>';
										?>
										<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="<?php echo '?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama)?>"><i class="fa fa-search"></i></a>
										<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Usul BKN" data-toggle="modal" data-target="#kirimModal" data-nip="<?php echo $value->nip?>" data-usul="<?php echo $value->usul_id?>" data-layanan="<?php echo $value->layanan_id?>"><i class="fa fa-mail-forward"></i></a>
										
									</td>
									<td><?php echo $value->nomor_usul ?></td>
									<td><?php echo $value->tgl ?></td>
									<td><?php echo $value->nip ?></td>
									<td><?php echo $value->nama_pns ?></td>
									<td><?php echo $value->nama_janda_duda ?></td>
									<td><?php echo $value->layanan_nama ?></td>									
									<td>
										<?php 
										if(!empty($value->file_pengantar))
										{
											$file = $value->file_pengantar;
											
											echo '<span data-toggle="tooltip" data-original-title="Ada File Pengantar">
											<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?t='.$this->myencrypt->encode("application/pdf").'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
										}
										else
										{
											echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Pengantar">
											<i class="fa fa-file-o" style="color:red;"></i></span>';
										}
										?>
									</td>
									<td><?php echo $value->updated_date ?></td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
                            </tbody>
                            </table>
                        </div>
						
					</div>
                </div>
            </div> 	
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<!-- Modal -->
	<div class="modal fade" id="lihatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="">Infomasi Kelengkapan Berkas</h4>
				</div>
							
			</div>
		</div>	
    </div>
	
	<div class="modal" id="showFile" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog  md-dialog modal-lg">
		  <div class="modal-content md-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >File Dokumen</h4>
				</div>	
				<div class="modal-body md-body">
					<iframe  id="frame" width="100%" height="100%" frameborder="0" ></iframe>					
				</div>
		  </div>
		</div>
	</div>	

	<div class="modal fade" id="kirimModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmKirim">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin Usul ini akan dikirim ke BKN ?</div>
                       <input type="hidden" name="usul_nip"/>	
					   <input type="hidden" name="usul_id"/>
					   <input type="hidden" name="usul_layanan"/>
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnKirim"><i class="fa fa-leaf"></i>&nbsp;OK Kirim !</button>
				</div>
			</div>
		</div>	
	</div>
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$(".select2").select2({
			width: '100%'
		});
				
		$('.datetimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#showFile').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/taspen/getInlineTaspen/'+id);			
	    });
		
		$('.table-responsive').on("click",'a[href="#edit"]',function(e){
			var nip     =  $(this).attr('data-nip'),
			    nomor   =  $(this).attr('data-nomor'),
				tgl     =  $(this).attr('data-tgl'),
				layanan =  $(this).attr('data-layanan'),
				nama	=  $(this).attr('data-nama'),
				jd		=  $(this).attr('data-jd'),
				nopen   =  $(this).attr('data-nopen'),
				golongan   =  $(this).attr('data-golongan'),
				jabatan    =  $(this).attr('data-jabatan'),
				perkawinan =  $(this).attr('data-perkawinan'),
				meninggal  =  $(this).attr('data-meninggal'),
				unit  	   =  $(this).attr('data-unit'),				
				gapok      =  $(this).attr('data-gapok'),
				penpok     =  $(this).attr('data-penpok'),
				alamat     =  $(this).attr('data-alamat'),
				usul_id    =  $(this).attr('data-usul');
				
            $("input[name=nomor_usul]").val(nomor);	
			$("input[name=tgl_usul]").val(tgl);
			$("[name=layanan_id]").val(layanan);
			$("input[name=nama_pns]").val(nama);
			$("input[name=nama_janda_duda]").val(jd);
			$("input[name=nopen]").val(nopen);
			$("input[name=usul_id]").val(usul_id);
			$("[name=nip]").val(nip);
			$("[name=nip]").select2().trigger('change');
			
			$("[name=golongan]").val(golongan);
			$("input[name=jabatan]").val(jabatan);
			$("input[name=unit_kerja]").val(unit);
			$("input[name=tgl_perkawinan]").val(perkawinan);
			$("input[name=meninggal_dunia]").val(meninggal);
			$("input[name=gaji_pokok_terakhir]").val(gapok);
			$("input[name=pensiun_pokok_terakhir]").val(penpok);
			$("input[name=alamat]").val(alamat);
		}); 
		
		$('#kirimModal').on('hide.bs.modal',function(e){
			$("#nBtnKirim").show();
		});
		
		$('#kirimModal').on('show.bs.modal',function(e){
		     $('#kirimModal #msg').text('Konfirmasi Pengiriman Usul')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip'),
				layanan	=  $(e.relatedTarget).attr('data-layanan'),
				usul    =  $(e.relatedTarget).attr('data-usul');
			
			$('#kirimModal input[name=usul_nip]').val(nip);
			$('#kirimModal input[name=usul_id]').val(usul);
			$('#kirimModal input[name=usul_layanan]').val(layanan);
		});
		
		$("#nBtnKirim").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmKirim').serialize();
			
			$('#kirimModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/taspen/kirim",
				data: data,
				success: function(){					
					$('#kirimModal #msg').text('Usul sudah dikirim ke BKN....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();	
					$("#nBtnKirim").hide();
			    }, // akhir fungsi sukses
				error: function(res){
					$('#kirimModal #msg').text(res.responseJSON.pesan)
						.removeClass( "text-blue")
						.addClass( "text-red" );
					refreshTable();	
					$("#nBtnKirim").hide();
				}	
		    });
			return false;
		});
		
		
		$('#lihatModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/taspen/getKelengkapan/'+id, function(data){
				$('#lihatModal').find('.modal-header').html(data); 
				
			});			
	    });
		
		$("[name=layanan_id]").on("change", function(e){
			if($(this).val() == 16) $("#tlabel").text("Janda/Duda");
			if($(this).val() == 17) $("#tlabel").text("Yatim/Piatu");
			
		});	
		
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/taspen/getUsulAll',   
			    data: $('form[name=frmUsul]').serialize(),
			    success: function(res) {
					$("#tb-usul").html(res);
				},
			});
		}		
	});
	</script>
	<script type="text/javascript">
		<?php if($show):?>
		Swal.fire(
			"<?php echo $title?>",
			"<?php echo $pesan; ?>",
			"<?php echo $tipe?>"
		) 
		<?php endif;?> 
	</script>
	</body>
</html>
