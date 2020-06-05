<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('vheader');?> 
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
</head> 	
<style>
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
		    <section class="content">
			    <div class="row">
					<div class="col-md-12">
						<h4><b>LIST NOMINATIF&nbsp; - <?php echo $detail_agenda->layanan_nama ?>&nbsp; - No Usul : <?php echo $detail_agenda->agenda_nousul ?> -  Jumlah <?php echo $detail_agenda->agenda_jumlah ?></b></h4>
					</div>
				</div>
			  
			  <?php if($detail_agenda->agenda_status == 'dibuat'){?>
				<!--IMPORT EXCEL HIDDEN!-->
				<div class="row">
					<label class="control-label col-md-2"><a href="<?php echo site_url().'/agenda/getXls/'?>" target="_blank" data-tooltip="tooltip" title="Download Format File Import Nominatif">Format File KLIK DISINI</a></label>
					<div class="col-md-10">
					  <?php echo form_open_multipart('importexcel');?>
					  <div class="input-group">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_id ?>" name="input_agendaid" required>
						<input type="file" class="form-control" name="xls_ins" required>
						<span class="input-group-btn">
						  <button type="submit" class="btn btn-primary" type="button">Import Nominatif</button>
						</span>
					  </div>
					  </form>
					</div>
				</div>
				<!--IMPORT EXCEL HIDDEN-->

			    <div class="row">
					<div class="col-md-4">
					  <?php echo form_open_multipart('agenda/ftambah_nominatif');?>
					  <div class="input-group">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_id ?>" name="input_agendaid">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_id ?>" name="input_layananid">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_nama ?>" name="input_layanannama">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_grup ?>" name="input_layanangrup">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->kp_periode ?>" name="input_periodekp">
						<input type="text" class="form-control" name="input_nip" value="" maxlength="19" id="searchbox1" required>
						<span class="input-group-btn">
						  <button type="submit" class="btn btn-primary" type="button">Tambah</button>
						</span>
					  </div>
					  <div id="results1" class="form-control"></div>
					  </form>
					</div>
					<div class="col-md-8">				 
						<form>						
							<div class="col-md-6">
								<input type="text" class="form-control" name="nip" id="nip" disabled><br>
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" name="nama" id="nama"  disabled><br>
							</div>						  
							
							
							<div class="col-md-2">
							   <input type="text" class="form-control" name="golongan" id="golongan" disabled><br>
							</div>
							<div class="col-md-10">
								<input type="text" class="form-control" name="pendidikan" id="pendidikan"  disabled><br>
							</div>
															  
							<div class="col-md-12">
								<input type="text" class="form-control" name="instansi" id="instansi"  disabled><br>
							</div>
							 
						</form>				  
					</div>
			    </div>
			    <?php } ?>
				
		        <div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
						    <div class="box-header with-border">
							  <h3 class="box-title">Daftar Nominatif Usul</h3>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<table id="tblnomi" class="table table-striped">
									  <thead>
									  <tr>
										<th>No</th>
										<th>NIP</th>
										<th>Nama</th>
										<th>Gol/Ruang</th>
										<th>Pendidikan</th>
										<th>Instansi</th>
										<th>Aksi</th>
									  </tr>
									  </tr>
									  </thead>
									  <tbody>
										<?php $no=1; foreach ($list_nominatif as $nominatif){ ?>
										<tr>
										  <td><?php echo $no++; ?></td>
										  <td><?php echo $nominatif->nip; ?></td>
										  <td><?php if($nominatif->pns_pnsnam != NULL){ echo $nominatif->pns_pnsnam; }else{echo "<b>PERIKSA KEMBALI NIP INI  DAN IMPORT KEMBALI</b>";} ?></td>
										  <td><?php echo $nominatif->gol_golnam; ?>-<?php echo $nominatif->gol_pktnam; ?></td>
										  <td><?php echo $nominatif->dik_namdik; ?></td>
										  <td><?php echo $nominatif->ins_namins; ?></td>
										  <?php if($detail_agenda->agenda_status == 'dibuat'){?>
										  <td>
											<a onclick="confirmation(event)" href="<?php echo site_url("agenda/hapus_nominatif/$nominatif->nip/$nominatif->agenda_id")?>" type="button" data-tooltip="tooltip" title="Hapus Nominatif" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-trash"></i></a>
										  </td>
										<?php }else{echo "<td></td>"; } ?>
										</tr>
									  <?php } ?>
									  </tbody>
									</table>
								</div>
							</div>	
							<div class="box-footer">
								<?php echo form_open_multipart('agenda/kirim_usul');?>
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_id ?>" name="input_agendaid">
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_jumlah ?>" name="input_agendajumlah">
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->kp_periode ?>" name="input_periodekp">
								   <?php if($detail_agenda->agenda_status == 'dibuat'){?>
									<button type="submit" class="btn btn-block btn-primary btn-flat" name="button"><b>KIRIM USUL </b><i class="fa fa-angle-double-right"></i></button>
								   <?php } ?>						
								</form>	
							</div>
						</div>	
					</div>		
			    </div>
				
				
			
		  </section><!-- /.content -->
		</div><!-- /.content-wrapper -->
	</div><!-- ./wrapper -->
	
<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>  
	var nip, nama, golongan, pendidikan, instansi;
	function kirim(nip, nama, golongan, pendidikan, instansi){
		 var y = $("#nip").val(nip);
		 var y = $("#nama").val(nama);
		 var y = $("#golongan").val(golongan);
		 var y = $("#pendidikan").val(pendidikan);
		 var y = $("#instansi").val(instansi);

		 $("#results1").slideUp('fast');
	}
	
	function confirmation(ev) {
		  ev.preventDefault();
		  var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
		  //console.log(urlToRedirect); // verify if this is the right URL
		  Swal.fire({
			title: 'Yakin Ingin Menghapus Data ini?',
			text: "Data yang dihapus tidak bisa dikembalikan!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!'
		  })
		  .then((result) => {
			if (result.value) {
				location.replace(urlToRedirect)
			}
		});
	}
	
    $(document).ready(function () {
        $('[data-tooltip="tooltip"]').tooltip();
		 
		$("#searchbox1").on('keyup',function () {
			this.value = $.trim(this.value);
			var key = $(this).val();
			if(key.length > 14)
			{	  
			    $.ajax({
					url:'<?php echo site_url(); ?>/agenda/autocomplete',
					type:'GET',
					data:'kirim='+key,
					beforeSend:function () {
					  $("#results1").slideUp('fast');
					},
					success:function (data) {
					  $("#results1").html(data);
					  $("#results1").slideDown('fast');
					}
			    });
			}	  
		});
    });
	<?php if($show):?>
	 Swal.fire(
		"<?php echo $title;?>",
		"<?php echo $pesan;?>",
		"<?php echo $tipe;?>",
	  ) 
	<?php endif;?>
</script>
</body>
</html>