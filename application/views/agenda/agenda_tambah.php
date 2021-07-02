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
			<div style="margin-top:-20px;margin-bottom:-10px;margin-left:10px;"><h3><b>Tambah Agenda</b></h3></div>
		  </section>
		  <!-- Main content -->
		  <section class="content">
			<div class="row">
			  <div class="col-md-12">
				<div class="box box-primary">
				  <div class="box-body"><br>
					<?php echo form_open_multipart('agenda/ftambah'); ?>
					  <div class="form-group">
						<div class="row">
						  <div class="col-md-3">
							<label>No Usul / No Surat :</label>
							  <input type="text" name="input_nousul" class="form-control" placeholder="No Usul" required>
						   </div>
						   <div class="col-md-3">
							 <label>Dokumen Pengantar :</label>
							   <input type="file" name="input_dokumen" class="form-control" placeholder="Dokumen Pengantar" required>
							</div>
						   <div class="col-md-4">
							<label>Pilih Layanan :</label>
							<select class="form-control" name="input_layanan" required>
								<option value="">Pilih Layanan</option>
								<?php foreach ($list_layanan as $layanan) { ?>
								<option value="<?php echo $layanan->layanan_id ?>"><?php echo $layanan->layanan_nama ?></option>
								<?php } ?>
							</select>
						   </div>
						   <div class="col-md-2">
							 <label>Jumlah Nominatif :</label>
							   <input type="number" name="input_jumlah" class="form-control" placeholder="Jumlah Nominatif" required>
							</div>
						</div><br>


						<div class="row">
							 <div class="col-md-12">
								 <button type="submit" class="btn btn-block btn-primary btn-flat"><i class="fa fa-save"></i>&nbsp;&nbsp; <b>Buat Agenda</b></button>
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