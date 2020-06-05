<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
    <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/dropzone/dropzone.css">
	
	<style>
  </style>
  </head>
  <body class="skin-yellow sidebar-mini sidebar-collapse">
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
		    <form id="upload" action="<?php echo site_url()?>/photo/doUpload" class="dropzone">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
			</form>
			<div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Tabel Format Foto</h3>
						</div><!-- /.box-header -->					
						<div class="table-responsive">
							<table class="table table-striped">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Foto</th>
									<th>Format</th>
									<th>File</th>
									<th>Keterangan</th>													
								</tr>
							</thead>   
							<tbody>	
							   	<tr>
									<td>1</td>
									<td>Kartu Istri</td>
									<td>KARIS_NIP atau NIP_KARIS</td>	
									<th>jpg</th>
									<td>Pas Foto KARIS berwarna atau hitam putih Ukuran 3x4</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Kartu Suami</td>
									<td>KARSU_NIP atau NIP_KARSU</td>	
									<th>jpg</th>
									<td>Pas Photo KARSU berwarna atau hitam putih Ukuran 3x4</td>
								</tr>
								<tr>
									<td>3</td>
									<td>Kartu Pegawai</td>
									<td>KARPEG_NIP atau NIP_KARPEG</td>	
									<th>jpg</th>
									<td>Pas Photo KARPEG berwarna atau hitam putih Ukuran 3x4</td>
								</tr>
							</tbody>
							</table>
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
	<script src="<?php echo base_url()?>assets/plugins/dropzone/dropzone.js"></script>
	
	<script>	
	$(document).ready(function () {
        Dropzone.options.upload = {
			dictDefaultMessage: "Letakkan photo yang akan di upload disini",
			acceptedFiles: ".jpg"
		};
		
	});	
   </script>
  </body>
</html>
