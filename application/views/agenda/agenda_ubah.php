<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('vheader');?>	
</head> 	
<style>
</style>
</head>
<body class="hold-transition skin-yellow">
<div class="wrapper">	
    <header class="main-header">
	<!-- Logo -->
	<a href="#" class="logo">
	  <!-- mini logo for sidebar mini 50x50 pixels -->
	  <span class="logo-mini"><b>A</b></span>
	  <!-- logo for regular state and mobile devices -->
	  <span class="logo-lg">AdminPanel</span>
	</a>
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
			<div style="margin-top:-20px;margin-bottom:-10px;margin-left:10px;"><h3><b>Ubah Agenda</b></h3></div>
		  </section>
		  <!-- Main content -->
		  <section class="content">
			<div class="row">
			  <div class="col-md-12">
				<div class="box box-primary">
				  <div class="box-body"><br>
					<?php echo form_open_multipart('agenda/fubah'); ?>
					  <div class="form-group">
						<div class="row">
						  <div class="col-md-3">
							<input type="hidden" name="input_id" value="<?php echo $detail_agenda->agenda_id ?>">
							<input type="hidden" name="input_dokumen_sblm" value="<?php echo $detail_agenda->agenda_dokumen ?>">
							<label>No Nota Usul :</label>
							  <input type="text" name="input_nousul" value="<?php echo $detail_agenda->agenda_nousul ?>" class="form-control" placeholder="No Usul" required>
						   </div>
						   <div class="col-md-3">
							 <label>Dokumen Pengantar (<a href="<?php echo site_url()."/agenda/getPdf/".$this->myencrypt->encode($detail_agenda->agenda_ins)."/".$this->myencrypt->encode($detail_agenda->agenda_dokumen)?>") target="_blank">Sebelumnya</a>)</label>
							   <input type="file" name="input_dokumen" class="form-control" placeholder="Dokumen Pengantar">
							</div>
						   <div class="col-md-4">
							<label>Pilih Layanan :</label>
							<select class="form-control" name="input_layanan" required>
								<option value="<?php echo $detail_agenda->layanan_id ?>"><?php echo $detail_agenda->layanan_nama ?></option>
								<?php foreach ($list_layanan as $layanan) { ?>
								<option value="<?php echo $layanan->layanan_id ?>"><?php echo $layanan->layanan_nama ?></option>
								<?php } ?>
							</select>
						   </div>
						   <div class="col-md-2">
							 <label>Jumlah Nominatif :</label>
							   <input type="number" name="input_jumlah" class="form-control" value="<?php echo $detail_agenda->agenda_jumlah ?>" placeholder="Jumlah Nominatif" required>
							</div>
						</div>


						<div class="row">
							 <div class="col-md-12">
								 <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-edit"></i>&nbsp;&nbsp; <b>Ubah Agenda</b></button>
							 </div>
						</div>
					  </div><!-- /.form group -->
					<?php form_close(); ?><!-- /.form -->
				  </div><!-- ./box-body-->
				</div><!-- ./box-->
			  </div><!-- ./col-->
			</div><!-- ./row-->
		  </section><!-- ./content-->
		</div><!-- ./content-wrapper -->
	</div><!-- ./wrapper -->
<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
</body>
</html>